<?php
return [
    'driver'   => 'mysql',             // ✅ Correcto: PDO usa 'mysql' también para MariaDB
    'engine'   => 'mariadb',           // ✅ Es buena práctica indicar 'mariadb' si vas a validarlo
    'host'     => '127.0.0.1',         // ✅ Correcto si estás en local
    'port'     => '3306',              // ⚠️ Asegúrate que tu MariaDB escuche en este puerto
    'database' => 'db_cadep',     // ✅ Cambia si tu base tiene otro nombre
    'username' => 'root',              // ✅ Cambia si tu usuario no es root
    'password' => 'mjgm2003**',        // ✅ Tu contraseña
    'charset'  => 'utf8mb4'            // ✅ Recomendado para soporte Unicode completo
];
