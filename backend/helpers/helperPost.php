<?php

// Configuraciones necesarias
header("Content-Type: application/json");

// Incluir el controlador y otros archivos necesarios
require_once __DIR__ . "/../../api/controllers/post.controller.php"; // Ajusta la ruta si es necesario
require_once __DIR__ . "/ResponseHelper.php";
require_once __DIR__ . "/../../api/models/post.model.php"; 
require_once __DIR__ . "/../../api/models/connection.php";

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



// Obtener datos del cuerpo de la solicitud POST
$input = json_decode(file_get_contents("php://input"), true);

// Validar que se hayan enviado datos necesarios
if (isset($input["table"]) && isset($input["data"])) {

    // Llamar al controlador
    PostController::postData(
        $input["table"],
        $input["data"]
    );

} else {
    // Si no se pasan parámetros obligatorios
    echo ResponseHelper::notFound();
    http_response_code(404);
}
