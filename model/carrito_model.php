<?php
require_once '../config/conexion.php';

class CarritoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Crear un carrito nuevo para un usuario
    public function crearCarrito($id_usuario) {
        $sql = "INSERT INTO carrito (id_usuario, fecha_creacion) VALUES (?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $this->pdo->lastInsertId();
    }

    // Buscar carrito activo de un usuario
    public function obtenerCarritoPorUsuario($id_usuario) {
        $sql = "SELECT * FROM carrito WHERE id_usuario = ? ORDER BY fecha_creacion DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
