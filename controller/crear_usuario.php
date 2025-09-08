<?php
// Verificar si la conexión a la base de datos ($pdo) está definida
if (!isset($pdo)) {
    require_once '../config/conexion.php';
}

// Verificar si se recibieron los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $rol = trim($_POST['rol'] ?? '');

    // Validar que todos los campos estén completos
    if (!empty($nombre_completo) && !empty($email) && !empty($password) && !empty($rol)) {
        try {
            // Insertar el usuario en la base de datos
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, email, password, id_rol) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $nombre_completo,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $rol
            ]);

            // Redirigir de vuelta a la página de gestión de usuarios con éxito
            header("Location: ../view/gestion_usuarios.php?success=1");
            exit;
        } catch (PDOException $e) {
            error_log("Error al crear el usuario: " . $e->getMessage());
            echo "Error al crear el usuario. Por favor, inténtelo de nuevo.";
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    echo "Método no permitido.";
}