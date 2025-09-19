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

    // Simulación de pago (puedes cambiar la lógica según lo que desees)
    $pago_exitoso = true; // Cambia a false para simular error

    if ($pago_exitoso) {
        // Aquí podrías actualizar el estado del pedido a 'pagado'
        // $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'pagado' WHERE id_pedido = ?");
        // $stmt->execute([$id_pedido]);
        header("Location: ../view/carrito.php?pago=exitoso");
        exit;
    } else {
        header("Location: ../view/carrito.php?pago=fallido");
        exit;
    }
}
?>