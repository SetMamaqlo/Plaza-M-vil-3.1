<?php
// filepath: c:\xampp\htdocs\Plaza_Movil\model\gestion_prod.php
require_once '../config/conexion.php';

class ProductosModel
{
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }
    public function obtenerProductos()
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            p.id, 
            p.nombre, 
            p.descripcion, 
            p.precio, 
            p.categoria, 
            u.nombre_completo AS vendedor
        FROM productos p
        LEFT JOIN usuarios u ON p.id_usuario = u.id
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarProducto($nombre, $descripcion, $precio, $categoria_id)
    {
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, categoria_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $precio, $categoria_id]);
    }

    public function actualizarProducto($id, $nombre, $descripcion, $precio, $categoria_id)
    {
        $stmt = $this->pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, categoria_id = ? WHERE id = ?");
        return $stmt->execute([$nombre, $descripcion, $precio, $categoria_id, $id]);
    }

    public function eliminarProducto($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerValoresEnum($tabla, $columna)
    {
        $stmt = $this->pdo->prepare("SHOW COLUMNS FROM $tabla LIKE ?");
        $stmt->execute([$columna]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Extraer los valores del ENUM
        preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
        $enumValues = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return $enumValues;
    }
}