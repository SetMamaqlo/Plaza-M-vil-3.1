<?php
require_once '../config/conexion.php';
require_once '../vendor/autoload.php';

use MercadoPago\SDK;
use MercadoPago\Payment;

// Configurar MercadoPago
SDK::setAccessToken("APP_USR-2180958071478070-092210-ac4ee3a8d1cff42421efa9d6ddd087f1-2702024581"); // token real de pruebas/producción

$payment_id = $_GET['payment_id'] ?? null;
$payment = null;
if ($payment_id) {
    $payment = \MercadoPago\Payment::find_by_id($payment_id);
}

if ($payment) {
    $status = $payment->status;
    $amount = $payment->transaction_amount;
    $method = $payment->payment_method_id;

    // 1. Actualizar registro de pagos con payment_id real
    $stmt = $pdo->prepare("UPDATE pagos 
                               SET estado = ?, metodo = ?, transaccion_id = ? 
                               WHERE preference_id = ?");
    $stmt->execute([$status, $method, $payment_id, $preference_id]);

    // 2. Obtener pedido asociado
    $stmt = $pdo->prepare("SELECT id_pedido FROM pagos WHERE preference_id = ?");
    $stmt->execute([$preference_id]);
    $pago = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pago) {
        $id_pedido = $pago['id_pedido'];

        if ($status === 'approved') {
            // 3. Marcar pedido como pagado
            $stmt = $pdo->prepare("UPDATE pedidos SET estado = 'pagado' WHERE id_pedido = ?");
            $stmt->execute([$id_pedido]);

            // 4. Obtener productos del pedido
            $stmt = $pdo->prepare("SELECT id_producto, cantidad FROM pedido_detalle WHERE id_pedido = ?");
            $stmt->execute([$id_pedido]);
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 5. Descontar stock
            foreach ($productos as $prod) {
                $stmtUpdate = $pdo->prepare("UPDATE productos 
                                                 SET stock = stock - :cantidad 
                                                 WHERE id_producto = :id_producto");
                $stmtUpdate->execute([
                    ':cantidad'   => $prod['cantidad'],
                    ':id_producto'=> $prod['id_producto']
                ]);
            }

            // 6. Vaciar carrito (usando id_usuario del pedido)
            $stmt = $pdo->prepare("SELECT id_usuario FROM pedidos WHERE id_pedido = ?");
            $stmt->execute([$id_pedido]);
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($pedido) {
                $id_usuario = $pedido['id_usuario'];

                // Eliminar detalle del carrito
                $stmt = $pdo->prepare("DELETE FROM carrito_detalle WHERE id_carrito IN 
                                           (SELECT id_carrito FROM carrito WHERE id_usuario = ?)");
                $stmt->execute([$id_usuario]);

                // Eliminar carrito
                $stmt = $pdo->prepare("DELETE FROM carrito WHERE id_usuario = ?");
                $stmt->execute([$id_usuario]);
            }
        }
    }
} else {
    $status = 'error';
    $amount = 0;
    $method = '';
    echo "<div class='alert alert-danger'>No se encontró información del pago.</div>";
}

// 7. Mensaje + redirección
echo "<h1>Pago $status</h1>";
echo "<script>
                setTimeout(function(){
                    window.location.href = '../index.php';
                }, 5000);
              </script>";