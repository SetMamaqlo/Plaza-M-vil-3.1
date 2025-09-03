<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    try {
        // Eliminar el usuario de la base de datos
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id_usuario]);

        // Redirigir con el parámetro deleted=1
        header("Location: ../view/gestion_usuarios.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        // Redirigir con un mensaje de error
        header("Location: ../view/gestion_usuarios.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Redirigir si la solicitud no es válida
    header("Location: ../view/gestion_usuarios.php?error=Solicitud no válida");
    exit;
}