<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['user_id_usuario'])) {
    header("Location: ../view/login.php");
    exit;
}

// Simulación de pago (puedes cambiar la lógica según lo que desees)
$pago_exitoso = true; // Cambia a false para simular error

if ($pago_exitoso) {
    header("Location: ../view/carrito.php?pago=exitoso");
    exit;
} else {
    header("Location: ../view/carrito.php?pago=fallido");
    exit;
}
?>