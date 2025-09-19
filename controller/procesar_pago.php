<?php
require_once '../config/conexion.php';
require_once '../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

session_start();

// Verificar usuario
if (!isset($_SESSION['user_id_usuario'])) {
    header("Location: ../view/login.php");
    exit;
}

$id_usuario = $_SESSION['user_id_usuario'];
$id_pedido = $_POST['id_pedido'] ?? null;

if (!$id_pedido) {
    die("No se recibió un pedido válido.");
}

// Traer productos del pedido
$stmt = $pdo->prepare("
    SELECT pd.*, p.nombre, p.precio_unitario 
    FROM pedido_detalle pd
    JOIN productos p ON pd.id_producto = p.id_producto
    WHERE pd.id_pedido = ?
");
$stmt->execute([$id_pedido]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$productos) {
    die("No se encontraron productos en el pedido.");
}

// Configurar MercadoPago
MercadoPagoConfig::setAccessToken("APP_USR-4480789578558929-091821-52f62413327355e4a017c4853860fdfa-2701669732"); // token real de pruebas/producción

$items = [];
$total = 0;
foreach ($productos as $prod) {
    $items[] = [
        "title" => $prod['nombre'],
        "quantity" => (int) $prod['cantidad'],
        "currency_id" => "COP",
        "unit_price" => (float) $prod['precio_unitario']
    ];
    $total += $prod['cantidad'] * $prod['precio_unitario'];
}

$client = new PreferenceClient();
try {
    $preference = $client->create([
        "items" => $items,
        "back_urls" => [
            "success" => "http://localhost/Plaza-M-vil-3.1/controller/confirmar_pago.php?status=success&payment_id={payment.id}&preference_id={preference.id}",
            "failure" => "http://localhost/Plaza-M-vil-3.1/controller/confirmar_pago.php?status=failure&payment_id={payment.id}&preference_id={preference.id}",
            "pending" => "http://localhost/Plaza-M-vil-3.1/controller/confirmar_pago.php?status=pending&payment_id={payment.id}&preference_id={preference.id}"
        ],
        "auto_return" => "approved"
    ]);
} catch (\MercadoPago\Exceptions\MPApiException $e) {
    echo "<pre>";
    print_r($e->getApiResponse()->getContent()); 
    echo "</pre>";
    exit;
}

// Guardar el registro inicial en la tabla pagos con estado "pendiente"
// Usamos preference_id temporalmente, luego en confirmar_pago.php lo actualizamos con payment_id real
$stmt = $pdo->prepare("INSERT INTO pagos (id_venta, proveedor, transaccion_id, monto, moneda, estado, metodo)
                       VALUES (?, 'MercadoPago', ?, ?, 'COP', 'pendiente', 'checkout')");
$stmt->execute([$id_pedido, $preference->id, $total]);

// Redirigir al checkout de MercadoPago
header("Location: " . $preference->init_point);
exit;
