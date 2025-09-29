<?php
require_once '../config/conexion.php';
require_once '../vendor/autoload.php';

session_start();

// ✅ Verificar usuario logueado
if (!isset($_SESSION['user_id_usuario'])) {
    header("Location: ../view/login.php");
    exit;
}

$id_usuario = $_SESSION['user_id_usuario'];
$id_pedido = $_POST['id_pedido'] ?? null;

if (!$id_pedido) {
    die("❌ No se recibió un pedido válido.");
}

// ✅ Obtener productos del pedido
$stmt = $pdo->prepare("
    SELECT pd.*, p.nombre, p.precio_unitario 
    FROM pedido_detalle pd
    JOIN productos p ON pd.id_producto = p.id_producto
    WHERE pd.id_pedido = ?
");
$stmt->execute([$id_pedido]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$productos) {
    die("❌ No se encontraron productos en el pedido.");
}

// ✅ Configurar credenciales (usa TEST primero, cambia por PRODUCTION después)
MercadoPago\SDK::setAccessToken("TEST-XXXXXXXXXXXXXXXXXXXXXXXXX");

// ✅ Crear ítems para la preferencia
$items = [];
$total = 0;
foreach ($productos as $prod) {
    $item = new MercadoPago\Item();
    $item->title = $prod['nombre'];
    $item->quantity = (int) $prod['cantidad'];
    $item->currency_id = "COP";
    $item->unit_price = (float) $prod['precio_unitario'];

    $items[] = $item;
    $total += $prod['cantidad'] * $prod['precio_unitario'];
}

// ✅ Crear preferencia
$preference = new MercadoPago\Preference();
$preference->items = $items;
$preference->back_urls = [
    "success" => "http://localhost/Plaza-M-vil-3.1/controller/confirmar_pago.php?status=success",
    "failure" => "http://localhost/Plaza-M-vil-3.1/controller/confirmar_pago.php?status=failure",
    "pending" => "http://localhost/Plaza-M-vil-3.1/controller/confirmar_pago.php?status=pending"
];
$preference->auto_return = "approved";
$preference->save();

// ✅ Guardar pago en la BD
$stmt = $pdo->prepare("
    INSERT INTO pagos (id_pedido, proveedor, transaccion_id, monto, moneda, estado, metodo, fecha)
    VALUES (?, 'MercadoPago', ?, ?, 'COP', 'pendiente', 'checkout', NOW())
");
$stmt->execute([$id_pedido, $preference->id, $total]);

// ✅ Redirigir al checkout
header("Location: " . $preference->init_point);
exit;
