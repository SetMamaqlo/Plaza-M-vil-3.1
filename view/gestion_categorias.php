<?php
// filepath: c:\xampp\htdocs\Plaza_Movil\view\gestion_categorias.php
session_start();
require_once '../controller/gestion_categorias.php';

// Verificar si el usuario tiene el rol de administrador
$id_rol = $_SESSION['user_id_rol'] ?? null;
if ($id_rol !== 1) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <!-- Navbar -->
    <?php include '../navbar.php'; ?>

    <!-- Espacio para que el contenido no quede oculto bajo la navbar fija -->
    <div style="height:70px"></div>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestión de Categorías</h1>

        <!-- Formulario para agregar una nueva categoría -->
        <div class="mb-4">
            <form action="../controller/gestion_categorias.php" method="POST" class="d-flex">
                <input type="hidden" name="accion" value="agregar">
                <input type="text" name="nueva_categoria" class="form-control me-2" placeholder="Nueva Categoría" required>
                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>

        <!-- Tabla de categorías -->
        <table class="table table-bordered table-hover">
            <thead class="table-success">
                <tr>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                <tr>
                    <td><?php echo htmlspecialchars($categoria); ?></td>
                    <td>
                        <!-- Formulario para eliminar una categoría -->
                        <form action="../controller/gestion_categorias.php" method="POST" class="d-inline">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">
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