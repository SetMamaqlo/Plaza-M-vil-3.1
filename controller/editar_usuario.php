<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $nombre_completo = $_POST['nombre_completo'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($id_usuario && !empty($nombre_completo) && !empty($email) && !empty($role)) {
        try {
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre_completo = ?, email = ?, id_rol = ? WHERE id_usuario = ?");
            $stmt->execute([$nombre_completo, $email, $role, $id_usuario]);

            header("Location: ../view/gestion_usuarios.php?success=1");
            exit;
        } catch (PDOException $e) {
            echo "Error al actualizar el usuario: " . $e->getMessage();
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    echo "Método no permitido.";
}