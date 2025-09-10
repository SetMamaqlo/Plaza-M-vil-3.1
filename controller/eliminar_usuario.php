<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;

    if ($id_usuario) {
        try {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            // Redirigir de vuelta a la página de gestión de usuarios con éxito
            header("Location: ../view/gestion_usuarios.php?deleted=1");
            exit;
        } catch (PDOException $e) {
            error_log("Error al eliminar el usuario: " . $e->getMessage());
            echo "Error al eliminar el usuario.";
        }
    } else {
        echo "ID de usuario no proporcionado.";
    }
} else {
    echo "Método no permitido.";
}