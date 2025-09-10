<?php
<<<<<<< HEAD
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
=======
// Asegurarse de que no haya salidas antes de modificar encabezados
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIr__. '../config/conexion.php';



$categorias = [];

try {
    $stmt = $pdo->query("SELECT id_categoria, nombre AS nombre_categoria FROM categoria ORDER BY nombre ASC");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error al obtener categorías: " . $e->getMessage());
}
>>>>>>> 92ab89c9009590a8c09f6f208a61171111e0e0c5
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="/Plaza-M-vil-3.1/css/styles.css">

<nav class="navbar navbar-expand-sm navbar-light bg-light-green fixed-top">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="/Plaza-M-vil-3.1/index.php">
            <img src="/Plaza-M-vil-3.1/img/logohorizontal.png" alt="Logo" style="height: 40px;">
        </a>

        <!-- Botón móvil -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <!-- Inicio -->
                <li class="nav-item">
                    <a class="nav-link" href="/Plaza-M-vil-3.1/index.php">Inicio</a>
                </li>

                <!-- ¿Quiénes Somos? -->
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">¿Quiénes Somos?</a>
                </li>

                <!-- Categorías -->
                <li class="nav-item dropdown">
<<<<<<< HEAD
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Categorías</a>
=======
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Categorías
                    </a>
>>>>>>> 92ab89c9009590a8c09f6f208a61171111e0e0c5
                    <ul class="dropdown-menu">
                        <?php foreach ($categorias as $cat): ?>
                            <li>
                                <a class="dropdown-item" href="/Plaza-M-vil-3.1/index.php?id_categoria=<?php echo $cat['id_categoria']; ?>">
                                    <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <!-- Dashboard (Admin) -->
                <?php if (!empty($_SESSION['user_id_rol']) && (int)$_SESSION['user_id_rol'] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Plaza-M-vil-3.1/view/dashboard.php">Dashboard</a>
                    </li>
                <?php endif; ?>

                <!-- Carrito -->
                <li class="nav-item">
                    <a class="nav-link position-relative" href="/Plaza-M-vil-3.1/view/carritoview.php">
                        <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>
                        </span>
                    </a>
                </li>

                <!-- Usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Usuario
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/Plaza-M-vil-3.1/view/perfil.php">Mi Perfil</a></li>
                        <?php if (!empty($_SESSION['user_id_rol']) && (int)$_SESSION['user_id_rol'] == 3): ?>
                            <li><a class="dropdown-item" href="/Plaza-M-vil-3.1/view/mis_productos.php">Mis Productos</a></li>
                        <?php endif; ?>
                        <?php if (!empty($_SESSION['user_id_rol']) && (int)$_SESSION['user_id_rol'] == 1): ?>
                            <li><a class="dropdown-item" href="/Plaza-M-vil-3.1/view/dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/Plaza-M-vil-3.1/controller/logincontroller.php?action=logout">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>