<?php
require_once '../config/conexion.php';
require_once '../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

MercadoPagoConfig::setAccessToken("APP_USR-2180958071478070-092210-ac4ee3a8d1cff42421efa9d6ddd087f1-2702024581");

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
