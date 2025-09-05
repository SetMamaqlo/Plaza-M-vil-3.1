<?php
// filepath: c:\xampp\htdocs\Plaza_Movil\controller\gestion_categorias.php
require_once '../model/categorias_model.php';
require_once '../config/conexion.php';

class CategoriaController {
    private $model;

    public function __construct($pdo) {
        $this->model = new CategoriasModel($pdo);
    }

    public function obtenerCategorias(): array {
        return $this->model->obtenerCategorias();
    }

    public function agregarCategoria($nombre, $descripcion): void {
        $this->model->agregarCategoria($nombre, $descripcion);
    }

    public function eliminarCategoria($id_categoria): void {
        $this->model->eliminarCategoria($id_categoria);
    }
}

// Crear instancia del controlador
$controller = new CategoriaController($pdo);

// Manejar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'agregar') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $controller->agregarCategoria($nombre, $descripcion);

        } elseif ($_POST['accion'] === 'eliminar') {
            $id_categoria = $_POST['id_categoria'] ?? null;
            if ($id_categoria) {
                $controller->eliminarCategoria($id_categoria);
            }
        }
    }

    header("Location: ../view/gestion_categorias.php");
    exit;
}

// Obtener todas las categorías (para la vista)
$categorias = $controller->obtenerCategorias();

