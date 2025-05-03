<?php

class Connection
{
  /*==========================
  DATABASE CONFIG (DINÁMICO)
  ==========================*/
  static public function infoDatabase()
  {
    return require __DIR__ . '/../config/database.php';
  }

  /*==========================
  DATABASE CONNECTION FLEXIBLE
  ==========================*/
  static public function connect()
  {
    $config = self::infoDatabase();

    $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";

    try {
      $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
      ]);

      // Validación de motor
      $stmt = $pdo->query("SELECT VERSION() as version");
      $version = $stmt->fetchColumn();

      if ($config['engine'] === 'mariadb' && stripos($version, 'MariaDB') === false) {
        throw new Exception("Error: Esperabas MariaDB pero se detectó MySQL u otro motor.");
      }

      if ($config['engine'] === 'mysql' && stripos($version, 'MariaDB') !== false) {
        throw new Exception("Error: Esperabas MySQL pero se detectó MariaDB.");
      }

      return $pdo;

    } catch (PDOException $e) {
      die("❌ Error de conexión: " . $e->getMessage());
    } catch (Exception $e) {
      die("⚠️ Verificación fallida: " . $e->getMessage());
    }
  }

  /*==========================
  Validar columnas de una tabla
  ==========================*/
  static public function getColumnsData($table, $columns)
  {
    $database = self::infoDatabase()['database'];

    $validate = self::connect()
      ->query("SELECT COLUMN_NAME AS item FROM information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
      ->fetchAll(PDO::FETCH_OBJ);

    if (empty($validate)) return null;

    if ($columns[0] === "*") array_shift($columns);

    $sum = 0;
    foreach ($validate as $value) {
      $sum += in_array($value->item, $columns);
    }

    return $sum == count($columns) ? $validate : null;
  }
}