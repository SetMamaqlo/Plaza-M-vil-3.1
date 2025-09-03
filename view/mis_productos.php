<?php
session_start();
require_once '../config/conexion.php';

// Solo vendedores pueden acceder
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendedor') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['user_role'] ?? null;

// Obtener productos del vendedor
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id_usuario = ? ORDER BY fecha_publicacion DESC");
$stmt->execute([$user_id]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmtCategorias = $pdo->query("SELECT DISTINCT categoria FROM productos ORDER BY categoria ASC");
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php if (isset($_GET['edit']) && $_GET['edit'] === 'ok'): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            ¡Producto actualizado correctamente!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    <?php include '../navbar.php'; ?>
    <div style="height:70px"></div>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Mis Productos</h2>
            <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                <i class="bi bi-plus-circle"></i> Añadir Producto
            </a>
        </div>
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="../img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top"
                            alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <p class="card-text"><strong>Precio:</strong> $<?php echo number_format($producto['precio']); ?>
                            </p>
                            <p class="card-text"><strong>Categoría:</strong>
                                <?php echo htmlspecialchars($producto['categoria']); ?></p>
                            <button class="btn btn-outline-primary w-100 mt-2" onclick="editarProducto(
                                    <?php echo $producto['id']; ?>,
                                    '<?php echo htmlspecialchars(addslashes($producto['nombre'])); ?>',
                                    '<?php echo htmlspecialchars(addslashes($producto['descripcion'])); ?>',
                                    '<?php echo $producto['precio']; ?>',
                                    '<?php echo htmlspecialchars(addslashes($producto['categoria'])); ?>'
                                )">
                                <i class="bi bi-pencil"></i> Editar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($productos)): ?>
                <div class="col-12 text-center text-muted">No tienes productos publicados.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal para editar producto -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formEditarProducto" method="POST" action="../controller/editarproducto.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_producto" id="edit_id_producto">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="edit_categoria" name="categoria" required>
                            <option value="" disabled>Selecciona una categoría</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo htmlspecialchars($categoria['categoria']); ?>">
                                    <?php echo htmlspecialchars($categoria['categoria']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="edit_precio" name="precio" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para agregar producto -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formAgregarProducto" method="POST"
                action="../controller/productcontroller.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarLabel">Añadir Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="add_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="add_descripcion" name="descripcion" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="add_precio" class="form-label">Precio</label>
                        <input type="number" class="form-control" id="add_precio" name="precio" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="add_categoria" name="categoria" required>
                            <option value="" disabled selected>Selecciona una categoría</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo htmlspecialchars($categoria['categoria']); ?>">
                                    <?php echo htmlspecialchars($categoria['categoria']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_imagen" class="form-label">Imagen</label>
                        <input type="file" class="form-control" id="add_imagen" name="imagen" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Añadir Producto</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editarProducto(id, nombre, descripcion, precio, categoria) {
            document.getElementById('edit_id_producto').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_descripcion').value = descripcion;
            document.getElementById('edit_precio').value = precio;
            document.getElementById('edit_categoria').value = categoria;
            var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
            modal.show();
        }
    </script>
</body>

</html>