<?php
$config = require 'config.php';

// Obtener la URL
$mysql_url = $config['mysql_url'];

try {
    $pdo = new PDO($mysql_url);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos";
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}