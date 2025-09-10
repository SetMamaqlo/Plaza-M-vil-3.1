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
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include '../navbar.php'; ?>

    <!-- Espacio para que el contenido no quede oculto bajo la navbar fija -->
    <div style="height:70px"></div>

    <div class="container mt-5">
        <div class="card p-4">
            <h1 class="text-center mb-4">Gestión de Usuarios</h1>

            <!-- Botón para volver al dashboard -->
            <div class="text-start mb-3">
                <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver al
                    Dashboard</a>
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
                        <form action="../controller/crear_usuario.php" method="POST" onsubmit="return validarFormulario();">
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
                                    <label for="username" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Rol</label>
                                    <select name="rol" id="role" class="form-select" required>
                                        <option value="1">Administrador</option>
                                        <option value="2">Vendedor</option>
                                        <option value="3">Comprador</option>
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
            <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
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
                document.addEventListener('DOMContentLoaded', function () {
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                });
            </script>
            <?php endif; ?>

            <!-- Tabla de usuarios -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
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
                            <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre_completo']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <form action="../controller/editar_usuario.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                    <select name="rol" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="1" <?php echo $usuario['id_rol'] == 1 ? 'selected' : ''; ?>>
                                            Administrador</option>
                                        <option value="2" <?php echo $usuario['id_rol'] == 2 ? 'selected' : ''; ?>>
                                            Vendedor</option>
                                        <option value="3" <?php echo $usuario['id_rol'] == 3 ? 'selected' : ''; ?>>
                                            Comprador</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <!-- Botón para abrir el modal de edición -->
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editarModal<?php echo $usuario['id_usuario']; ?>">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>

                                <!-- Modal de edición -->
                                <div class="modal fade" id="editarModal<?php echo $usuario['id_usuario']; ?>" tabindex="-1"
                                    aria-labelledby="editarModalLabel<?php echo $usuario['id_usuario']; ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editarModalLabel<?php echo $usuario['id_usuario']; ?>">
                                                    Editar Usuario</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="../controller/editar_usuario.php" method="POST" onsubmit="return validarFormularioEdicion();">
                                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nombre_completo" class="form-label">Nombre Completo
                                                        </label>
                                                        <input type="text" class="form-control" id="nombre_completo"
                                                            name="nombre_completo"
                                                            value="<?php echo htmlspecialchars($usuario['nombre_completo']); ?>"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Nombre de Usuario</label>
                                                        <input type="text" class="form-control" id="username" name="username"
                                                            value="<?php echo htmlspecialchars($usuario['username'] ?? ''); ?>"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email"
                                                            value="<?php echo htmlspecialchars($usuario['email']); ?>"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="rol" class="form-label">Rol</label>
                                                        <select name="rol" id="rol" class="form-select" required>
                                                            <option value="1" <?php echo $usuario['id_rol'] == 1 ? 'selected' : ''; ?>>
                                                                Administrador</option>
                                                            <option value="2" <?php echo $usuario['id_rol'] == 2 ? 'selected' : ''; ?>>
                                                                Vendedor</option>
                                                            <option value="3" <?php echo $usuario['id_rol'] == 3 ? 'selected' : ''; ?>>
                                                                Comprador</option>
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
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validarFormulario() {
            const campos = ['nombre_completo', 'email', 'password', 'username', 'role'];
            for (const campo of campos) {
                const valor = document.getElementById(campo).value.trim();
                if (!valor) {
                    alert(`El campo ${campo} es obligatorio.`);
                    return false;
                }
            }
            return true;
        }

        function validarFormularioEdicion() {
            const campos = ['nombre_completo', 'username', 'email', 'rol'];
            for (const campo of campos) {
                const valor = document.getElementById(campo).value.trim();
                if (!valor) {
                    alert(`El campo ${campo} es obligatorio.`);
                    return false;
                }
            }
            return true;
        }
    </script>
</body>

</html>