<?php
$host = 'sql111.infinityfree.com';
$dbname = 'if0_40007067_XXX';
$username = 'if0_40007067';
$password = 'RXnp8F2RN64NJz';

//$host = 'localhost';
//$dbname = 'agro_app';
//$username = 'root';
//$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}