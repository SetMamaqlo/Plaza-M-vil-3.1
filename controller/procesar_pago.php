<?php
require_once '../config/conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'] ?? null;
    $id_producto = $_POST['id_producto'] ?? null;
    $id_cliente = $_SESSION['user_id_usuario'] ?? null;
    $monto_total = $_POST['monto_total'] ?? null;
    $metodo = $_POST['metodo'] ?? null;

    if (!$id_pedido || !$id_producto || !$id_cliente || !$monto_total || !$metodo) {
        die("Error: Datos incompletos.");
    }

    $stmt = $pdo->prepare("INSERT INTO pagos 
        (id_pedido, id_producto, id_cliente, monto_total, metodo, fecha_pago)
        VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$id_pedido, $id_producto, $id_cliente, $monto_total, $metodo]);

    header("Location: ../view/gestion_pagos.php?success=1");
    exit;
}
?>