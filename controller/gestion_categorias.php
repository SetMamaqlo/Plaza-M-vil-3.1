<?php
// filepath: c:\xampp\htdocs\Plaza_Movil\controller\gestion_categorias.php
require_once '../model/categorias_model.php';

$categoriasModel = new CategoriasModel();

// Manejar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'agregar') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $categoriasModel->agregarCategoria($nombre, $descripcion);

        } elseif ($_POST['accion'] === 'eliminar') {
            $id_categoria = $_POST['id_categoria'] ?? null;
            if ($id_categoria) {
                $categoriasModel->eliminarCategoria($id_categoria);
            }
        }
    }
    header("Location: ../view/gestion_categorias.php");
    exit;
}

// Obtener todas las categorías
$categorias = $categoriasModel->obtenerCategorias();