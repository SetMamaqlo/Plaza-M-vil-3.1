<?php
session_start();

// Si el usuario no ha iniciado sesión, redirige al login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit();
}
require_once '../config/conexion.php';

// Obtiene los datos enviados por el formulario, o valores vacíos si no existen
$user_id = $_SESSION['user_id'];
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$usuario = $_POST['usuario'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$foto_perfil = null;

// Si se subió una nueva foto de perfil y no hubo error
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['foto_perfil']['tmp_name'];// Ruta temporal del archivo subido
    $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION); // Obtiene la extensión del archivo
    $foto_perfil = 'perfil_' . $user_id . '_' . time() . '.' . $ext; // Genera un nombre único para la foto de perfil
    move_uploaded_file($tmp_name, __DIR__ . '/../img/' . $foto_perfil); // Mueve la foto a la carpeta img
}
// Si los campos obligatorios están completos
if ($nombre && $correo && $usuario) {
    // Si hay nueva foto, actualiza todos los campos incluyendo la foto
    if ($foto_perfil) {
        $stmt = $pdo->prepare('UPDATE usuarios SET nombre_completo = ?, email = ?, username = ?, telefono = ?, foto_perfil = ? WHERE id = ?');
        $stmt->execute([$nombre, $correo, $usuario, $telefono, $foto_perfil, $user_id]);
    } // Si no hay nueva foto, actualiza los demás campos}
    else {
        $stmt = $pdo->prepare('UPDATE usuarios SET nombre_completo = ?, email = ?, username = ?, telefono = ? WHERE id = ?');
        $stmt->execute([$nombre, $correo, $usuario, $telefono, $user_id]);
    }
    $_SESSION['user_name'] = $nombre; // Actualiza el nombre en la sesión
    header('Location: ../view/perfil.php?success=1'); // Redirige al perfil con mensaje de éxito
    exit();
} 
// Si faltan campos, redirige con error
else {
    header('Location: ../view/editar_perfil.php?error=1');
    exit();
}
