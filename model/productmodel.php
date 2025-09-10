<?php
class ProductModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addProduct($nombre, $descripcion, $precio_unitario, $foto, $id_agricultor, $stock, $id_categoria, $id_unidad, $fecha_publicacion) {
        // Ajusta la consulta para usar la columna 'categoria'
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre, descripcion, precio_unitario, foto, id_agricultor, stock, id_categoria, id_unidad, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombre, $descripcion, $precio_unitario, $foto, $id_agricultor, $stock, $id_categoria, $id_unidad, $fecha_publicacion])) {
            return $this->pdo->lastInsertId();
        } else {
            return false;
        }
    }

    public function getPdo() {
        return $this->pdo;
    }
}


