<?php
// filepath: c:\xampp\htdocs\Plaza_Movil\controller\gestion_categorias.php
require_once '../model/categorias_model.php';

$categoriasModel = new CategoriasModel();

// Manejar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'agregar') {
            $categoriasModel->agregarCategoria($_POST['nueva_categoria']);
        } elseif ($_POST['accion'] === 'eliminar') {
            $categoriasModel->eliminarCategoria($_POST['categoria']);
        }
    }
    header("Location: ../view/gestion_categorias.php");
    exit;
}

// Obtener categorías para la vista
$categorias = $categoriasModel->obtenerCategorias();