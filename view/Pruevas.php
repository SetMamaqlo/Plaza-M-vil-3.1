<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiénes Somos - Plaza Móvil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="bg-success bg-gradient text-white text-center py-5">
        <div class="container">
            <h1 class="fw-bold display-5">Quiénes Somos</h1>
            <p class="lead">Conectamos el campo colombiano con tu mesa, ofreciendo productos frescos y de calidad.</p>
        </div>
    </section>

    <!-- Nuestra Historia -->
    <section class="container my-5">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <img src="img/campo.jpg" alt="Campo colombiano" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h2 class="fw-bold text-success mb-3">Nuestra Historia</h2>
                <p class="text-muted">
                    Plaza Móvil nació con la visión de apoyar a los agricultores locales y ofrecer a los consumidores
                    productos frescos directamente del campo. 
                </p>
                <p class="text-muted">
                    Creemos en la economía local, en prácticas sostenibles y en brindar un canal digital moderno para que
                    campesinos y compradores puedan conectarse sin intermediarios.
                </p>
            </div>
        </div>
    </section>

    <!-- Misión y Visión -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-bullseye text-success display-4 mb-3"></i>
                            <h3 class="fw-bold text-success">Misión</h3>
                            <p class="text-muted">
                                Nuestra misión es facilitar el acceso a productos frescos, fortalecer el campo colombiano 
                                y brindar una experiencia de compra moderna y confiable.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-eye text-success display-4 mb-3"></i>
                            <h3 class="fw-bold text-success">Visión</h3>
                            <p class="text-muted">
                                Ser la plataforma líder en Colombia para la comercialización de productos agrícolas,
                                reconocida por su impacto social y sostenibilidad.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nuestro Equipo -->
    <section class="container my-5">
        <h2 class="text-center fw-bold text-success mb-5">Nuestro Equipo</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <img src="img/person1.jpg" class="card-img-top rounded-top" alt="Fundador">
                    <div class="card-body">
                        <h5 class="fw-semibold">Juan Pérez</h5>
                        <p class="text-muted small">Fundador & CEO</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <img src="img/person2.jpg" class="card-img-top rounded-top" alt="Co-Fundadora">
                    <div class="card-body">
                        <h5 class="fw-semibold">María López</h5>
                        <p class="text-muted small">Co-Fundadora & Marketing</p>
                    </div>
                </div>
            </div>
            <!-- Puedes agregar más miembros -->
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-success text-white text-center py-3">
        <p class="mb-0">&copy; 2025 Plaza Móvil. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
