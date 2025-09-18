<?php
//$host = 'sql100.infinityfree.com';
//$dbname = 'if0_39964705_agro_app';
//$username = 'if0_39964705';
//$password = 'xYV5ndRUDN';

$host = 'localhost';
$dbname = 'agro_app';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}