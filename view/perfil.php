<?php
session_start();
if (!isset($_SESSION['user_id_usuario'])) {
    header('Location: login.php');
    exit();
}
require_once '../config/conexion.php';
$user_id_usuario = $_SESSION['user_id_usuario'];
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id_usuario = ?');
$stmt->execute([$user_id_usuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo '<div class="alert alert-danger">Usuario no encontrado.</div>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
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
            <div class="text-center">
                <img src="<?php echo !empty($user['Foto']) ? '../img/' . htmlspecialchars($user['Foto']) : '../img/default_profile.png'; ?>"
                    alt="Foto de perfil" class="profile-img mb-3">
                <h2><?php echo htmlspecialchars($user['nombre_completo']); ?></h2>
                <p class="text-muted">Usuario: <?php echo htmlspecialchars($user['username']); ?></p>
                <p class="text-muted">Rol: <?php echo htmlspecialchars($user['id_rol']); ?></p>
            </div>

            <div class="mt-4">
                <h4>Información Personal</h4>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                    <li class="list-group-item"><strong>Teléfono:</strong>
                        <?php echo htmlspecialchars($user['telefono'] ?? 'No disponible'); ?></li>
                    <li class="list-group-item"><strong>Dirección:</strong>
                        <?php echo htmlspecialchars($user['direccion'] ?? 'No disponible'); ?></li>
                </ul>
            </div>

            <div class="mt-4 text-center">
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarPerfil"><i
                        class="bi bi-pencil"></i> Editar Perfil</button>
            </div>
        </div>
    </div>

    <!-- Modal para editar perfil -->
    <div class="modal fade" id="modalEditarPerfil" tabindex="-1" aria-labelledby="modalEditarPerfilLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formEditarPerfil" method="POST"
                action="../controller/editarperfilcontroller.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarPerfilLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($user['id_usuario']); ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            value="<?php echo htmlspecialchars($user['nombre_completo']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo"
                            value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario"
                            value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono"
                            value="<?php echo htmlspecialchars($user['telefono']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="foto_perfil" class="form-label">Foto de Perfil</label>
                        <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>