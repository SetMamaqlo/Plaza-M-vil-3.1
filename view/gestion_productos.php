<?php
include '../navbar.php'; 
require_once '../controller/gestion_productos.php';
// filepath: c:\xampp\htdocs\Plaza_Movil\view\gestion_productos.php

// Verificar si la sesión ya está activa antes de llamar a session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Asegurarse de que $id_rol sea un entero para evitar problemas de comparación estricta
$id_rol = isset($_SESSION['user_id_rol']) ? (int) $_SESSION['user_id_rol'] : null;

// Verificar si el usuario tiene el rol de administrador
if ($id_rol !== 1) {
    header("Location: ../index.php");
    exit;
}

// Mover ob_start() al inicio del archivo para evitar errores de encabezados ya enviados
ob_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <!-- Navbar -->
    

    <!-- Espacio para que el contenido no quede oculto bajo la navbar fija -->
    <div style="height:70px"></div>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Productos</h1>

        <!-- Botón para abrir el modal de creación de producto -->
        <div class="text-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearProductoModal">
                <i class="bi bi-plus-circle"></i> Crear Producto
            </button>
        </div>

        <!-- Tabla de productos -->
        <table class="table table-bordered table-hover">
    <thead class="table-success">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Categoría</th>
            <th>Vendedor</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?php echo htmlspecialchars($producto['id']); ?></td>
                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($producto['precio']); ?></td>
                <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                <td><?php echo htmlspecialchars($producto['vendedor']); ?></td>
                <td>
                    <!-- Botón para abrir el modal de edición -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                        data-bs-target="#editarProductoModal<?php echo $producto['id']; ?>">
                        <i class="bi bi-pencil"></i> Editar
                    </button>

                    <!-- Modal de edición -->
                    <div class="modal fade" id="editarProductoModal<?php echo $producto['id']; ?>" tabindex="-1"
                        aria-labelledby="editarProductoModalLabel<?php echo $producto['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="../controller/gestion_productos.php" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="editarProductoModalLabel<?php echo $producto['id']; ?>">Editar
                                            Producto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="accion" value="actualizar">
                                        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre"
                                                value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcion" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion"
                                                required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="precio" class="form-label">Precio</label>
                                            <input type="number" class="form-control" id="precio" name="precio"
                                                value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="categoria" class="form-label">Categoría</label>
                                            <select class="form-select" id="categoria" name="categoria" required>
                                                <?php foreach ($categorias as $categoria): ?>
                                                    <option value="<?php echo htmlspecialchars($categoria); ?>" 
                                                        <?php echo $producto['categoria'] === $categoria ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($categoria); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para eliminar -->
                    <form action="../controller/gestion_productos.php" method="POST" class="d-inline">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>