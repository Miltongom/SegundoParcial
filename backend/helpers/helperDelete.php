<?php

// Configuraciones necesarias
header("Content-Type: application/json");

// Incluir el modelo y helper necesarios
require_once __DIR__ . "/../../api/models/connection.php";
require_once __DIR__ . "/../../api/controllers/delete.controller.php"; // Deberás crear este archivo
require_once __DIR__ . "/ResponseHelper.php";

require_once __DIR__ . "/TokenValidator.php";

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


// Verificar que se pasen los parámetros necesarios
if (isset($_GET["table"]) && isset($_GET["id"]) && isset($_GET["idName"])) {

    $table = $_GET["table"];
    $id = $_GET["id"];
    $idName = $_GET["idName"]; // nombre del campo, como "id_usuario", "id_producto", etc.

    // Llamar al modelo para eliminar
    $response = DeleteController::deleteData($table, $id, $idName);

 

} else {
    echo ResponseHelper::notFound();
    http_response_code(404);
}

