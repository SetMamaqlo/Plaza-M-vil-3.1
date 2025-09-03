<?php
session_start();
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria']; // Recibe la categoría seleccionada

    // Actualiza el producto en la tabla de productos
    $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria = ? WHERE id = ? AND id_usuario = ?");
    $stmt->execute([$nombre, $descripcion, $precio, $categoria, $id_producto, $_SESSION['user_id']]);
}

// Redirige de vuelta a la página de mis productos
header("Location: ../view/mis_productos.php?edit=ok");
exit;
?>