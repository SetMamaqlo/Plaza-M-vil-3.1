<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../config/conexion.php';
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id_usuario = ?');
$stmt->execute([$user_id]);
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
    <title>Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/perfil.css"> <!-- Archivo CSS específico para este view -->
</head>
<body>
    <!-- Navbar -->
    <?php include '../navbar.php'; ?>

    <div class="container perfil-container">
        <h2 class="mb-4 text-center">Mi Perfil</h2>
        <div class="text-center mb-4">
            <?php if (!empty($user['foto'])): ?>
                <img src="../img/<?php echo htmlspecialchars($user['foto']); ?>" alt="Foto de perfil" class="rounded-circle shadow perfil-foto">
            <?php else: ?>
                <img src="../img/default_profile.png" alt="Foto de perfil" class="rounded-circle shadow perfil-foto">
            <?php endif; ?>
        </div>
        <table class="table table-bordered perfil-tabla">
            <tr><th>ID</th><td><?php echo htmlspecialchars($user['id_usuario']); ?></td></tr>
            <tr><th>Nombre</th><td><?php echo htmlspecialchars($user['nombre_completo']); ?></td></tr>
            <tr><th>Correo</th><td><?php echo htmlspecialchars($user['email']); ?></td></tr>
            <tr><th>Usuario</th><td><?php echo htmlspecialchars($user['username']); ?></td></tr>
            <tr><th>Teléfono</th><td><?php echo htmlspecialchars($user['telefono']); ?></td></tr>
            <tr><th>Rol</th><td><?php echo htmlspecialchars($user['id_rol']); ?></td></tr>
        </table>
        <div class="text-center mt-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarPerfil"><i class="bi bi-pencil"></i> Editar Datos</button>
            <a href="../index.php" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <!-- Modal para editar perfil -->
    <div class="modal fade" id="modalEditarPerfil" tabindex="-1" aria-labelledby="modalEditarPerfilLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formEditarPerfil" method="POST" action="../controller/editarperfilcontroller.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarPerfilLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($user['id_usuario']); ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user['nombre_completo']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>">
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