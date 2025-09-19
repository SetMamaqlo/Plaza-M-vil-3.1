<?php
require __DIR__ . '/../vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

// Configura el token (usa el de prueba mientras desarrollas)
MercadoPagoConfig::setAccessToken("APP_USR-4480789578558929-091821-52f62413327355e4a017c4853860fdfa-2701669732");

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
