<?php

header("Content-Type: application/json");

require_once __DIR__ . "/../../api/models/connection.php";
require_once __DIR__ . "/TokenValidator.php";

// Validar token
$headers = getallheaders();
if (!isset($headers["Authorization"])) {
    http_response_code(401);
    echo json_encode(["status" => 401, "error" => "Falta encabezado Authorization"]);
    exit;
}

$token = str_replace("Bearer ", "", $headers["Authorization"]);
$userSession = TokenValidator::validate($token);

if (!$userSession) {
    http_response_code(403);
    echo json_encode(["status" => 403, "error" => "Token inválido o expirado"]);
    exit;
}

// Leer el cuerpo como array asociativo
$input = json_decode(file_get_contents("php://input"), true);

// Validar estructura
if (
    isset($input["table"]) &&
    isset($input["nameId"]) &&
    isset($input["id"]) &&
    isset($input["data"]) &&
    is_array($input["data"])
) {
    $table = $input["table"];
    $nameId = $input["nameId"];
    $id = $input["id"];
    $data = $input["data"];

    try {
        $db = Connection::connect();

        // 🔎 Validar existencia del ID en la tabla
        $checkStmt = $db->prepare("SELECT COUNT(*) FROM $table WHERE $nameId = :id");
        $checkStmt->bindParam(":id", $id, PDO::PARAM_STR);
        $checkStmt->execute();

        if ($checkStmt->fetchColumn() == 0) {
            http_response_code(404);
            echo json_encode([
                "status" => 404,
                "error" => "El registro con ese ID no existe en la tabla '$table'"
            ]);
            exit;
        }

        // Construir la consulta SQL dinámicamente
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "$key = :$key,";
        }
        $set = rtrim($set, ",");

        $sql = "UPDATE $table SET $set WHERE $nameId = :id";
        $stmt = $db->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindParam(":$key", $data[$key], PDO::PARAM_STR);
        }
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        echo json_encode([
            "status" => 200,
            "rowsAffected" => $stmt->rowCount(),
            "comment" => "Actualización realizada con éxito"
        ]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "status" => 500,
            "error" => $e->getMessage(),
            "sql" => $sql ?? ""
        ]);
    }

} else {
    http_response_code(400);
    echo json_encode([
        "status" => 400,
        "error" => "Faltan parámetros o el formato es incorrecto",
        "method" => "put"
    ]);
}
