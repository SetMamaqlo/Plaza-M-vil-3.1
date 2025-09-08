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
            p.id_producto, 
            p.nombre, 
            p.descripcion, 
            p.precio_unitario, 
            c.nombre AS categoria
        FROM productos p
        JOIN categoria c ON p.id_categoria = c.id_categoria
        ORDER BY p.nombre ASC
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
        
        // Verificar si la clave del array existe antes de acceder a ella
        if (!isset($matches[1])) {
            error_log("La clave '1' no está definida en el array.");
            // Manejo de error o valor predeterminado
            $matches[1] = null;
        }

        $enumValues = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return $enumValues;
    }
}