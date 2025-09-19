<?php
// Inicia el búfer de salida para evitar errores de cabeceras
ob_start();
session_start();

// Verifica que los datos del formulario hayan sido enviados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pedido = $_POST['id_pedido'];
    $monto = $_POST['monto_total'];
    $metodo = $_POST['metodo'];
    $nombre_cliente = $_POST['nombre_cliente']; // Captura el nombre del cliente

    // Simulación del pago (50% de probabilidad de éxito)
    $probabilidad_exito = rand(0, 100);

    if ($probabilidad_exito > 50) {
        // Pago Exitoso
        $estado = "Completado";
        $transaccion_id = "TRANS-" . bin2hex(random_bytes(8));
        
        require_once 'gestion_pagos.php';
        crearPago($id_pedido, $monto, $metodo, $estado, $transaccion_id, $nombre_cliente);

        header("Location: gestion_pagos.php?estado=exitoso&transaccion_id=" . urlencode($transaccion_id));
        exit();
    } else {
        // Pago Fallido
        $estado = "Fallido";
        $transaccion_id = "NULL";
        
        require_once 'gestion_pagos.php';
        crearPago($id_pedido, $monto, $metodo, $estado, $transaccion_id, $nombre_cliente);
        
        header("Location: gestion_pagos.php?estado=fallido");
        exit();
    }
} else {
    // Si se accede directamente sin enviar el formulario
    header("Location: index.html");
    exit();
}
// Limpia y finaliza el búfer de salida
ob_end_flush();
?>