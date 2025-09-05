<?php
class ProductModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addProduct($id_agricultor, $id_categoria, $descripcion, $nombre, $stock, $precio_unitario, $id_unidad, $foto, $fecha_publicacion) {
        // Ajusta la consulta para usar la columna 'categoria'
        $stmt = $this->pdo->prepare("INSERT INTO productos (id_agricultor, id_categoria, descripcion, nombre, stock, precio_unitario, id_unidad, foto, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        if ($stmt->execute([$id_agricultor, $id_categoria, $descripcion, $nombre, $stock, $precio_unitario, $id_unidad, $foto, $fecha_publicacion]))
        {
            return $this -> pdo ->lastInsertId();
        } else{
            return false;
        }
    
    }
}

    