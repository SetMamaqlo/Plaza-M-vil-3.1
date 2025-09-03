<?php
$role = $_SESSION['user_role'] ?? null;
require_once __DIR__ . '/controller/notificaciones_controller.php';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+3i9zYkGm60D9e5e5e5e5e5e5e5e5" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ensure dropdowns are initialized
        var dropdownElements = document.querySelectorAll('.dropdown-toggle');
        dropdownElements.forEach(function (dropdown) {
            new bootstrap.Dropdown(dropdown);
        });
    });
</script>
<link rel="stylesheet" href="/Plaza-M-vil-3.1/css/styles.css">
<!-- Updated styles for buttons -->
<style>

</style>
<nav class="navbar navbar-expand-sm navbar-light bg-light-green fixed-top">
    <div class="container-fluid">
        <!-- Espacio para una imagen horizontal -->
        <a class="navbar-brand" href="/Plaza-M-vil-3.1/index.php"> <!-- Redirige al index.php -->
            <img src="/Plaza-M-vil-3.1/img/logohorizontal.png" alt="Logo" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <!-- Enlace de Inicio -->
                <li class="nav-item">
                    <a class="nav-link" href="/Plaza-M-vil-3.1/index.php">Inicio</a> <!-- Redirige al index.php -->
                </li>

                <!-- Enlace de ¿Quienes Somos? -->
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">¿Quienes Somos?</a>
                </li>

                <!-- Enlace de Categorías -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Categorías
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Frutas</a></li>
                        <li><a class="dropdown-item" href="#">Verduras</a></li>
                        <li><a class="dropdown-item" href="#">Lácteos</a></li>
                        <li><a class="dropdown-item" href="#">Bebidas</a></li>
                        <li><a class="dropdown-item" href="#">Carnes</a></li>
                        <li><a class="dropdown-item" href="#">Legumbres</a></li>
                    </ul>
                </li>

                <!-- Opciones según el rol -->
                <?php if ($role === 'administrador'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/Plaza-M-vil-3.1/view/dashboard.php">Dashboard</a>
                    </li>
                <?php endif; ?>

                <!-- Botón de Notificaciones -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell" style="font-size: 1.5rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="notification-count">
                            <?php echo isset($notificaciones) ? count($notificaciones) : 0; ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if (!empty($notificaciones)): ?>
                            <?php foreach ($notificaciones as $notificacion): ?>
                                <li><a class="dropdown-item" href="#"><?php echo htmlspecialchars($notificacion); ?></a></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><span class="dropdown-item text-muted">No hay notificaciones</span></li>
                        <?php endif; ?>
                    </ul>
                </li>

                <!-- Botón Carrito de Compras -->
                <li class="nav-item">
                    <a class="nav-link position-relative" href="/Plaza-M-vil-3.1/view/carritoview.php">
                        <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="cart-count">
                            <?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>
                        </span>
                    </a>
                </li>

                <!-- Menú de Usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Usuario
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end"> <!-- Alinea el menú hacia la derecha -->
                        <li><a class="dropdown-item" href="/Plaza-M-vil-3.1/view/perfil.php">Mi Perfil</a></li>
                        <?php if ($role === 'vendedor'): ?>
                            <li><a class="dropdown-item" href="/Plaza-M-vil-3.1/view/mis_productos.php">Mis Productos</a></li>
                        <?php endif; ?>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/Plaza-M-vil-3.1/controller/logincontroller.php?action=logout"
                                class="btn btn-danger">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>