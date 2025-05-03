<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/connection.php';


try {
    $pdo = Connection::connect();
    echo "✅ Conectado correctamente.<br>";

    $version = $pdo->query("SELECT VERSION() as version")->fetchColumn();
    echo "Versión de base de datos detectada: $version";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
