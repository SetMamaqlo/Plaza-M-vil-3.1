<?php
session_start();
require_once '../config/conexion.php';

// Depuración para verificar el método de la solicitud
error_log("Método de la solicitud: " . $_SERVER['REQUEST_METHOD']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Error: Este controlador solo acepta solicitudes POST.");
}

if (isset($_SESSION['user_id_usuario'])) {
    $id_producto = $_POST['id_producto'] ?? null;
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = $_POST['precio'] ?? 0;
    $categoria = $_POST['categoria'] ?? null;

    // Depuración para registrar el contenido de la solicitud
    error_log("Contenido de la solicitud: " . print_r($_POST, true));

    // Validar los datos antes de procesarlos
    if (empty($id_producto) || empty($nombre) || empty($descripcion) || empty($precio) || empty($categoria)) {
        error_log("Error: Todos los campos son obligatorios.");
        die("Error: Todos los campos son obligatorios.");
    }

    // Depuración para verificar los datos recibidos
    error_log("Datos recibidos: " . print_r($_POST, true));

    try {
        // Actualiza el producto en la tabla de productos
        $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio_unitario = ?, id_categoria = ? WHERE id_producto = ?");
        $stmt->execute([$nombre, $descripcion, $precio, $categoria, $id_producto]);

        // Redirige de vuelta a la página de mis productos
        header("Location: ../view/mis_productos.php?edit=ok");
        exit;
    } catch (PDOException $e) {
        error_log("Error al actualizar el producto: " . $e->getMessage());
        echo "Error al actualizar el producto.";
    }
} else {
    echo "Método no permitido.";
}
?>