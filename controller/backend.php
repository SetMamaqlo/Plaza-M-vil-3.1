<?php
// controller/backend.php

header('Content-Type: application/json; charset=utf-8');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$mensaje = strtolower(trim($data['mensaje'] ?? ''));

// Diccionario de respuestas
$respuestas = [
    "hola"       => "¡Hola! Bienvenido al chatbot 😊",
    "adios"      => "¡Hasta luego! 👋",
    "como estas" => "Estoy muy bien, gracias por preguntar 🤖",
    "productos"  => "📦 Puedes ver nuestros productos en la sección 'Productos'",
    "contacto"   => "📞 Puedes contactarnos al WhatsApp: +57 300 123 4567",
    "horario"    => "🕒 Nuestro horario de atención es de lunes a sábado de 8am a 6pm",
    "gracias"    => "Gracias por usar Plaza Móvil 🙌"
];

// Buscar respuesta o dar mensaje por defecto
$respuesta = $respuestas[$mensaje] ?? "🤖 Lo siento, no entendí tu mensaje. Intenta con: hola, productos, contacto, horario...";

echo json_encode(["respuesta" => $respuesta], JSON_UNESCAPED_UNICODE);
