<?php
require_once '../config/conexion.php';
session_start();
if (!isset($_SESSION['user_id_usuario'])) {
    echo "contenido de la variabel". $_SESSION['user_id_usuario'] ."tipo de dato". gettype($_SESSION['user_id_usuario']); 
    //header('Location: login.php');
   // exit();
}

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
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Editar Perfil</h2>
        <form action="../controller/editarperfilcontroller.php" method="POST" enctype="multipart/form-data">
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
                <?php if (!empty($user['foto_perfil'])): ?>
                    <div class="mt-2">
                        <img src="../img/<?php echo htmlspecialchars($user['foto_perfil']); ?>" alt="Foto actual" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="perfil.php" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>
</body>
</html>
