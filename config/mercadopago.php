<?php
require __DIR__ . '/../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

// Configura el token (usa el de prueba mientras desarrollas)
MercadoPagoConfig::setAccessToken("APP_USR-2180958071478070-092210-ac4ee3a8d1cff42421efa9d6ddd087f1-2702024581");

// Crear una preferencia de prueba
$client = new PreferenceClient();

$preference = $client->create([
    "items" => [
        [
            "title" => "Producto de prueba",
            "quantity" => 1,
            "currency_id" => "COP",
            "unit_price" => 1000
        ]
    ]
]);

echo "Link de pago: " . $preference->init_point;
