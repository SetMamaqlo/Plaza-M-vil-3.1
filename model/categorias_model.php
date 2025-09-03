<?php
// filepath: c:\xampp\htdocs\Plaza_Movil\model\categorias_model.php
require_once '../config/conexion.php';

class CategoriasModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function obtenerCategorias()
    {
        $stmt = $this->pdo->prepare("SHOW COLUMNS FROM productos LIKE 'categoria'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Extraer los valores del ENUM
        preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
        $categorias = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return $categorias;
    }

    public function agregarCategoria($nuevaCategoria)
    {
        // Obtener los valores actuales del ENUM
        $stmt = $this->pdo->prepare("SHOW COLUMNS FROM productos LIKE 'categoria'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Extraer los valores del ENUM
        preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
        $categorias = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        // Agregar la nueva categoría si no existe
        if (!in_array($nuevaCategoria, $categorias)) {
            $categorias[] = $nuevaCategoria;
            $nuevaDefinicion = "ENUM('" . implode("','", $categorias) . "')";

            // Modificar la columna para incluir la nueva categoría
            $stmt = $this->pdo->prepare("ALTER TABLE productos MODIFY COLUMN categoria $nuevaDefinicion NOT NULL");
            return $stmt->execute();
        }

        return false; // La categoría ya existe
    }

    public function eliminarCategoria($categoria)
    {
        // Obtener los valores actuales del ENUM
        $stmt = $this->pdo->prepare("SHOW COLUMNS FROM productos LIKE 'categoria'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Extraer los valores del ENUM
        preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
        $categorias = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        // Eliminar la categoría si existe
        if (in_array($categoria, $categorias)) {
            $categorias = array_filter($categorias, function ($value) use ($categoria) {
                return $value !== $categoria;
            });
            $nuevaDefinicion = "ENUM('" . implode("','", $categorias) . "')";

            // Modificar la columna para excluir la categoría
            $stmt = $this->pdo->prepare("ALTER TABLE productos MODIFY COLUMN categoria $nuevaDefinicion NOT NULL");
            return $stmt->execute();
        }

        return false; // La categoría no existe
    }
}