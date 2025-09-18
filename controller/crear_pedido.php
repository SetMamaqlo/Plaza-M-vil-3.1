<?php
session_start();
require_once '../config/conexion.php';
require_once '../model/PedidoModel.php';
require_once '../model/DetallePedidoModel.php';
require_once '../model/detalle_carrito_model.php';

if (!isset($_SESSION['user_id_usuario'])) {
    header("Location: ../view/login.php");
    exit;
}

$id_usuario = $_SESSION['user_id_usuario'];
$id_carrito = $_POST['id_carrito'] ?? null;

if (!$id_carrito) {
    die("Carrito no válido.");
}

$pedidoModel = new PedidoModel($pdo);
$detallePedidoModel = new DetallePedidoModel($pdo);
$detalleCarritoModel = new DetalleCarritoModel($pdo);

// 1. Crear pedido
$id_pedido = $pedidoModel->crearPedido($id_usuario);

// 2. Traer productos del carrito
$productosCarrito = $detalleCarritoModel->obtenerProductos($id_carrito);

// 3. Insertar cada producto en pedido_detalle
foreach ($productosCarrito as $prod) {
    $detallePedidoModel->agregarDetalle(
        $id_pedido,
        $prod['id_producto'],
        $prod['cantidad'],
        $prod['precio_unitario'],
        $prod['id_unidad']
    );
}

// 4. Vaciar carrito
$detalleCarritoModel->vaciarCarrito($id_carrito);

// 5. Redirigir al checkout (confirmación del pedido)
header("Location: ../view/pago.php?id_pedido=" . $id_pedido);
exit;
