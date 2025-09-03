<?php
session_start();
require_once '../config/conexion.php';
require_once '../model/categorias_model.php';

$categoriasModel = new CategoriasModel();

// Obtener categorías
$categorias = $categoriasModel->obtenerCategorias();

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Verificar si se ha enviado un ID de producto
$id_producto = $_POST['id_producto'] ?? null;
if (!$id_producto) {
    header("Location: ../view/carritoview.php");
    exit;
}

// Agregar solo el ID del producto al carrito
$_SESSION['carrito'][] = $id_producto;

// Redirigir al carrito
header("Location: ../view/carritoview.php");
exit;
?>