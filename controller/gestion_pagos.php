<?php
session_start();
require_once '../config/conexion.php';

// Verificar sesión
if (!isset($_SESSION['user_id_usuario'])) {
    header("Location: ../view/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['user_id_usuario'];
    $id_pedido = $_POST['id_pedido'] ?? null;
    $monto = $_POST['monto'] ?? null;

    if (!$id_pedido || !$monto) {
        header("Location: ../view/carritoview.php?status=error");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Insertar el pago
        $stmt = $pdo->prepare("
            INSERT INTO pagos (id_pedido, id_usuario, monto, metodo_pago, fecha_pago)
            VALUES (?, ?, ?, 'Tarjeta/Transferencia', NOW())
        ");
        $stmt->execute([$id_pedido, $id_usuario, $monto]);

        // Actualizar estado del pedido
        $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'pagado' WHERE id_pedido = ?");
        $stmt->execute([$id_pedido]);

        // Obtener carrito asociado al pedido
        $stmt = $pdo->prepare("
            SELECT c.id_carrito
            FROM pedidos p
            JOIN carrito c ON p.id_carrito = c.id_carrito
            WHERE p.id_pedido = ?
        ");
        $stmt->execute([$id_pedido]);
        $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($carrito) {
            $id_carrito = $carrito['id_carrito'];

            // Vaciar los detalles del carrito
            $stmt = $pdo->prepare("DELETE FROM detalle_carrito WHERE id_carrito = ?");
            $stmt->execute([$id_carrito]);
        }

        $pdo->commit();

        // Redirigir al carrito con mensaje de éxito
        header("Location: ../view/carritoview.php?status=success");
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error al procesar pago: " . $e->getMessage());
        header("Location: ../view/carritoview.php?status=error");
        exit;
    }
} else {
    header("Location: ../view/carritoview.php?status=error");
    exit;
}
