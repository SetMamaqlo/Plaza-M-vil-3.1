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

    
    // Lista TODAS las categorías
    public function obtenerCategorias(): array {
        $stmt = $this->pdo->query(
            "SELECT id_categoria, nombre, descripcion
             FROM categoria
             ORDER BY nombre ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca UNA por nombre y descripción (si la necesitas)
    public function buscarCategoria(string $nombre, string $descripcion): ?array {
        $stmt = $this->pdo->prepare(
            "SELECT id_categoria, nombre, descripcion
             FROM categoria
             WHERE nombre = ? AND descripcion = ?
             LIMIT 1"
        );
        $stmt->execute([$nombre, $descripcion]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function agregarCategoria($nombre, $descripcion)
    {
        // 1. Verificar si ya existe
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM categoria WHERE nombre = ?");
        $stmt->execute([$nombre]);
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            // Ya existe → no insertar
            return false;
        }

        // 2. Insertar la nueva categoría
        $stmt = $this->pdo->prepare(
            "INSERT INTO categoria (nombre, descripcion) VALUES (?, ?)"
        );
        return $stmt->execute([$nombre, $descripcion]);
    }

    public function eliminarcategoria($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM categoria WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function actualizarcategoria($id_categoria, $nombre, $descripcion)
    {
        $stmt = $this->pdo->prepare("UPDATE categoria SET nombre = ?, descripcion = ?, where id_categoria = ? ");
        return $stmt->execute([$id_categoria, $nombre, $descripcion, ]);
    }
}