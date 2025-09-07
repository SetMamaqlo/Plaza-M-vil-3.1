<?php
// filepath: c:\xampp\htdocs\Plaza_Movil\view\gestion_productos.php
session_start();
require_once '../config/conexion.php';
require_once '../model/productmodel.php';

$productoModel = new ProductosModel($pdo);
$productos = $productoModel->getProductos();

// Si no hay agricultor en sesión, lo asignamos de prueba (BORRAR en producción)
if (!isset($_SESSION['user_id_agricultor'])) {
    $_SESSION['user_id_agricultor'] = 1; 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <h1 class="mb-4">Gestión de Productos</h1>

    <!-- Formulario Crear Producto -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Agregar Producto</div>
        <div class="card-body">
            <form action="../controller/productcontroller.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="agregar">

                <div class="row mb-3">
                    <div class="col">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Descripción</label>
                        <input type="text" name="descripcion" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>Precio Unitario</label>
                        <input type="number" step="0.01" name="precio_unitario" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label>ID Categoría</label>
                        <input type="number" name="id_categoria" class="form-control" required>
                    </div>
                    <div class="col">
                        <label>ID Unidad</label>
                        <input type="number" name="id_unidad" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Foto</label>
                    <input type="file" name="foto" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Tabla de Productos -->
    <h2 class="mb-3">Listado de Productos</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Unidad</th>
                <th>Foto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id_producto']) ?></td>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td><?= htmlspecialchars($p['descripcion']) ?></td>
                <td><?= htmlspecialchars($p['stock']) ?></td>
                <td><?= htmlspecialchars($p['precio_unitario']) ?></td>
                <td><?= htmlspecialchars($p['id_categoria']) ?></td>
                <td><?= htmlspecialchars($p['id_unidad']) ?></td>
                <td>
                    <?php if (!empty($p['foto'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($p['foto']) ?>" width="60">
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Formulario Eliminar -->
                    <form action="../controller/productcontroller.php" method="POST" style="display:inline;">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este producto?')">Eliminar</button>
                    </form>

                    <!-- Botón Editar -->
                    <a href="editar_producto.php?id=<?= $p['id_producto'] ?>" class="btn btn-warning btn-sm">Editar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
