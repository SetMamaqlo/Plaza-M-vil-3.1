<?php
session_start();

require_once '../config/conexion.php';
require_once '../controller/medidas_controller.php';
require_once '../controller/gestion_categorias.php';

// Validar sesión y rol
if (!isset($_SESSION['user_id_usuario']) || $_SESSION['user_id_rol'] != 3) {
    error_log("Redirigiendo al index: Rol o sesión inválidos.");
    header("Location: ../index.php");
    exit;
}

$user_id_usuario = $_SESSION['user_id_usuario'];
$id_rol = $_SESSION['user_id_rol'] ?? null;
$id_agricultor = $_SESSION['user_id_agricultor'] ?? null;

// Depuración
error_log("ID Usuario: " . ($user_id_usuario ?? 'No definido'));
error_log("Rol: " . ($id_rol ?? 'No definido'));
error_log("ID Agricultor: " . ($id_agricultor ?? 'No definido'));

// Obtener productos del agricultor logueado
$stmt = $pdo->prepare("
    SELECT p.*, c.nombre AS categoria_nombre
    FROM productos p
    INNER JOIN categoria c ON p.id_categoria = c.id_categoria
    WHERE p.id_agricultor = ?
    ORDER BY p.fecha_publicacion DESC
");
$stmt->execute([$id_agricultor]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Depuración para verificar los productos obtenidos
error_log("Productos obtenidos: " . json_encode($productos));

// Obtener TODAS las categorías
$stmtCategorias = $pdo->query("SELECT id_categoria, nombre FROM categoria ORDER BY nombre ASC");
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
                        <img src="../img/<?php echo htmlspecialchars($producto['foto']); ?>" class="card-img-top"
                            alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <p class="card-text"><strong>Precio:</strong> $<?php echo number_format($producto['precio_unitario']); ?>
                            </p>
                            <p class="card-text"><strong>Categoría:</strong>
                                <?php echo htmlspecialchars($producto['categoria_nombre']); ?></p>
                            <button class="btn btn-outline-primary w-100 mt-2" onclick="editarProducto(
                                    <?php echo $producto['id_producto']; ?>,
                                    '<?php echo htmlspecialchars(addslashes($producto['nombre'])); ?>',
                                    '<?php echo htmlspecialchars(addslashes($producto['descripcion'])); ?>',
                                    '<?php echo $producto['precio_unitario']; ?>',
                                    '<?php echo htmlspecialchars(addslashes($producto['categoria_nombre'])); ?>'
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
                                <option value="<?php echo $row['id_categoria']; ?>">
                                    <?php echo htmlspecialchars($row['nombre']); ?>
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
                <h2 class="text-center mb-4">Añadir Nuevo Producto</h2>
                    <form action="../controller/productcontroller.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="precio_unitario" class="form-label">Precio Unitario</label>
                            <input type="number" step="0.01" class="form-control" id="precio_unitario" name="precio_unitario" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Disponible</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_unidad" class="form-label">Unidad de Medida</label>
                            <select class="form-control" id="id_unidad" name="id_unidad" required>
                                <option value="">-- Selecciona una unidad --</option>
                                <?php foreach ($medidas as $medida): ?>
                                    <option value="<?= htmlspecialchars($medida['id_unidad']) ?>">
                                        <?= htmlspecialchars($medida['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_categoria" class="form-label">Categoría</label>
                            <select class="form-control" id="id_categoria" name="id_categoria" required>
                                <option value="">-- Selecciona una categoría --</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat['id_categoria']) ?>">
                                        <?= htmlspecialchars($cat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_publicacion" class="form-label">Fecha de publicacion</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Imagen del Producto</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
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
        function editarProducto(id_producto, nombre, descripcion, precio_unitario, id_categoria) {
            document.getElementById('edit_id_producto').value = id_producto;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_descripcion').value = descripcion;
            document.getElementById('edit_precio').value = precio_unitario;
            document.getElementById('edit_id_categoria').value = id_categoria;
            var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
            modal.show();
        }
    </script>
</body>

</html>