<?php

// Configuraciones necesarias
header("Content-Type: application/json");

// Incluir el controlador
require_once __DIR__ . "/../../api/controllers/get.controller.php"; // Ajusta la ruta según tu estructura
require_once __DIR__ . "/ResponseHelper.php";    // Importante incluir el helper también
require_once __DIR__ . "/../../api/models/get.model.php";          // El modelo que usa el controlador
require_once __DIR__ . "/../../api/models/connection.php";         // Tu conexión a la base de datos
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

// Opcional: puedes usar $userSession["id_user"] para registrar quién hizo la operación



// Verificar si vienen parámetros por GET
if (isset($_GET["table"]) && isset($_GET["select"])) {

    // Opcionales
    $orderBy = $_GET["orderBy"] ?? null;
    $orderMode = $_GET["orderMode"] ?? null;
    $startAt = $_GET["startAt"] ?? null;
    $endAt = $_GET["endAt"] ?? null;

    // Llamar al controlador
    GetController::getData(
        $_GET["table"],
        $_GET["select"],
        $orderBy,
        $orderMode,
        $startAt,
        $endAt
    );

} else {
    // Si no se pasan parámetros obligatorios
    echo ResponseHelper::notFound();
    http_response_code(404);
}
