<?php
require_once '../config/conexion.php';
require_once '../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

MercadoPagoConfig::setAccessToken("APP_USR-4480789578558929-091821-52f62413327355e4a017c4853860fdfa-2701669732");

$payment_id = $_GET['payment_id'] ?? null;

if ($payment_id) {
    $client = new PaymentClient();
    $payment = $client->get($payment_id);

    $estado = $payment->status; // approved, rejected, pending
    $monto = $payment->transaction_amount;
    $metodo = $payment->payment_method_id;

    // Actualizar la tabla pagos
    $stmt = $pdo->prepare("UPDATE pagos 
                           SET estado = ?, metodo = ? 
                           WHERE transaccion_id = ?");
    $stmt->execute([$estado, $metodo, $payment_id]);

    echo "<h1>Pago $estado</h1>";
} else {
    echo "<h1>No se recibió un payment_id válido.</h1>";
}
