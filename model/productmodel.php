<?php
class ProductModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addProduct($nombre, $descripcion, $precio, $imagen, $id_usuario, $categoria) {
        // Ajusta la consulta para usar la columna 'categoria'
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, id_usuario, categoria, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$nombre, $descripcion, $precio, $imagen, $id_usuario, $categoria]);
    }
}
