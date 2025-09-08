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

    public function agregarProducto($nombre, $descripcion, $precio_unitario, $foto, $id_agricultor, $stock, $id_categoria, $id_unidad, $fecha_publicacion)
    {
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre, descripcion, precio_unitario, foto, id_agricultor, stock, id_categoria, id_unidad, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$nombre, $descripcion, $precio_unitario, $foto, $id_agricultor, $stock, $id_categoria, $id_unidad, $fecha_publicacion]);
    }

    public function actualizarProducto($id_producto, $nombre, $descripcion, $precio_unitario, $foto, $stock, $id_categoria, $id_unidad, $fecha_publicacion)
    {
        $stmt = $this->pdo->prepare("
            UPDATE productos 
            SET nombre = ?, 
                descripcion = ?, 
                precio_unitario = ?, 
                foto = ?, 
                stock = ?, 
                id_categoria = ?, 
                id_unidad = ?, 
                fecha_publicacion = ? 
            WHERE id_producto = ?
        ");

        return $stmt->execute([
            $nombre, 
            $descripcion, 
            $precio_unitario, 
            $foto, 
            $stock, 
            $id_categoria, 
            $id_unidad, 
            $fecha_publicacion, 
            $id_producto
        ]);
    }


    public function eliminarProducto($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id_producto = ?");
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