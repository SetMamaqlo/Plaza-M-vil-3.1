<?php
// filepath: c:\xampp\htdocs\Plaza_Movil\controller\gestion_productos.php
require_once '../model/gestion_prod.php';

$productosModel = new ProductosModel();

// Obtener productos
$productos = $productosModel->obtenerProductos();

// Obtener valores del ENUM
$categorias = $productosModel->obtenerValoresEnum('productos', 'categoria');

// Manejar solicitudes POST (Agregar, Actualizar, Eliminar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'agregar') {
            $productosModel->agregarProducto($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['categoria']);
        } elseif ($_POST['accion'] === 'actualizar') {
            $productosModel->actualizarProducto($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['categoria']);
        } elseif ($_POST['accion'] === 'eliminar') {
            $productosModel->eliminarProducto($_POST['id']);
        }
    }
    header("Location: ../view/gestion_productos.php");
    exit;
}

// Obtener productos para la vista
$productos = $productosModel->obtenerProductos();