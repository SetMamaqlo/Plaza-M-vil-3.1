<?php
session_start();
require_once '../config/conexion.php';

// Asegurarse de que $id_rol sea un entero para evitar problemas de comparación estricta
$id_rol = isset($_SESSION['user_id_rol']) ? (int) $_SESSION['user_id_rol'] : null;

// Verificar si el usuario tiene el rol de administrador
if ($id_rol !== 1) {
    header("Location: ../index.php");
    exit;
}

// Obtener la lista de usuarios
$stmt = $pdo->prepare("SELECT id_usuario, nombre_completo, email, id_rol FROM usuarios");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se debe mostrar el modal de éxito o eliminación
$success = isset($_GET['success']) && $_GET['success'] == 1;
$deleted = isset($_GET['deleted']) && $_GET['deleted'] == 1;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
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
        <?php if ($deleted): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>¡Éxito!</strong> El usuario ha sido eliminado con éxito.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <h1 class="text-center mb-4">Gestión de Usuarios</h1>

        <!-- Botón para volver al dashboard -->
        <div class="text-start mb-3">
            <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver al Dashboard</a>
        </div>

        <!-- Botón para abrir el modal de creación de usuario -->
        <div class="text-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
                <i class="bi bi-plus-circle"></i> Crear Usuario
            </button>
        </div>

        <!-- Modal para crear un nuevo usuario -->
        <div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearUsuarioModalLabel">Crear Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="../controller/crear_usuario.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nombre_completo" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre_completo" name="nombre_completo"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Rol</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="comprador">Comprador</option>
                                    <option value="vendedor">Vendedor</option>
                                    <option value="administrador">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Crear Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de éxito -->
        <?php if ($success): ?>
            <div class="modal fade show" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
                aria-hidden="true" style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="successModalLabel">Usuario Creado</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            El usuario ha sido creado con éxito.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            </script>
        <?php endif; ?>

        <!-- Tabla de usuarios -->
        <table class="table table-bordered table-hover">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <?php
                        // Verificar si la clave 'id' existe antes de acceder a ella
                        if (!isset($usuario['id'])) {
                            error_log("La clave 'id' no está definida en el array de usuario.");
                            $usuario['id'] = null; // Asignar un valor predeterminado si es necesario
                        }
                        ?>
                        <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre_completo']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <form action="../controller/editar_usuario.php" method="POST" class="d-inline">
                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="comprador" <?php echo isset($usuario['role']) && $usuario['role'] === 'comprador' ? 'selected' : ''; ?>>Comprador</option>
                                    <option value="vendedor" <?php echo isset($usuario['role']) && $usuario['role'] === 'vendedor' ? 'selected' : ''; ?>>Vendedor</option>
                                    <option value="administrador" <?php echo isset($usuario['role']) && $usuario['role'] === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <!-- Botón para abrir el modal de edición -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editarModal<?php echo $usuario['id']; ?>">
                                <i class="bi bi-pencil"></i> Editar
                            </button>

                            <!-- Modal de edición -->
                            <div class="modal fade" id="editarModal<?php echo $usuario['id']; ?>" tabindex="-1"
                                aria-labelledby="editarModalLabel<?php echo $usuario['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editarModalLabel<?php echo $usuario['id']; ?>">
                                                Editar Usuario</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="../controller/editar_usuario.php" method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="id_usuario"
                                                    value="<?php echo $usuario['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="nombre_completo" class="form-label">Nombre Completo</label>
                                                    <input type="text" class="form-control" id="nombre_completo"
                                                        name="nombre_completo"
                                                        value="<?php echo htmlspecialchars($usuario['nombre_completo']); ?>"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Rol</label>
                                                    <select name="role" id="role" class="form-select">
                                                        <option value="comprador" <?php echo $usuario['role'] === 'comprador' ? 'selected' : ''; ?>>Comprador</option>
                                                        <option value="vendedor" <?php echo $usuario['role'] === 'vendedor' ? 'selected' : ''; ?>>Vendedor</option>
                                                        <option value="administrador" <?php echo $usuario['role'] === 'administrador' ? 'selected' : ''; ?>>
                                                            Administrador</option>
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

                            <!-- Botón para abrir el modal de eliminación -->
                            <form action="../controller/eliminar_usuario.php" method="POST" class="d-inline">
                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Footer -->
        <footer class="bg-light text-center py-3 mt-5">
            <p class="mb-0">&copy; 2025 Plaza Móvil. Todos los derechos reservados.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>