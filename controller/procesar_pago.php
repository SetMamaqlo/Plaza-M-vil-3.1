<?php
session_start();

if (!isset($_SESSION['user_id_usuario'])) {
    header("Location: ../view/login.php");
    exit;
}

// Verifica que el carrito exista y tenga productos
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    $pago_exitoso = true; // Simulación de pago

    $id_usuario = $_SESSION['user_id_usuario'];
    $carrito = $_SESSION['carrito'];
    $monto_total = 0;
    foreach ($carrito as $producto) {
        $monto_total += $producto['precio_unitario'] * $producto['cantidad'];
    }
    $metodo = "Tarjeta";
    $nombre_cliente = isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : 'Cliente';

    if ($pago_exitoso) {
        // Registrar el pedido en la base de datos
        require_once '../model/pedido.php';
        $id_pedido = Pedido::registrar($id_usuario, $carrito);

        if (!$id_pedido) {
            header("Location: ../view/carrito.php?error=no_pedido");
            exit;
        }

        // Registrar el pago en la base de datos
        require_once '../model/pago_model.php';
        $transaccion_id = "TRANS-" . bin2hex(random_bytes(8));
        $estado = "Completado";
        PagoModel::crearPago($id_pedido, $monto_total, $metodo, $estado, $transaccion_id, $id_usuario, $nombre_cliente);

        unset($_SESSION['carrito']);
        $_SESSION['pedido_realizado'] = true;

        header("Location: ../view/carrito.php?pago=exitoso");
        exit;
    } else {
        $estado = "Fallido";
        $transaccion_id = "NULL";
        require_once '../model/pago_model.php';
        PagoModel::crearPago(null, $monto_total, $metodo, $estado, $transaccion_id, $id_usuario, $nombre_cliente);

        header("Location: ../view/carrito.php?pago=fallido");
        exit;
    }
} else {
    header("Location: ../view/carrito.php?error=no_pedido");
    exit;
}
?>