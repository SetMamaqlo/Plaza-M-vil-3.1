<?php
require_once '../config/conexion.php';

// Verificar si se recibieron los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo = $_POST['nombre_completo'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';

    if (!empty($nombre_completo) && !empty($email) && !empty($password) && !empty($role)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre_completo, $email, password_hash($password, PASSWORD_DEFAULT), $role]);

            // Redirigir de vuelta a la página de gestión de usuarios
            header("Location: ../view/gestion_usuarios.php");
            exit;
        } catch (PDOException $e) {
            echo "Error al crear el usuario: " . $e->getMessage();
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    echo "Método no permitido.";
}