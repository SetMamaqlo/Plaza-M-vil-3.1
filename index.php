<?php
session_start();
$id_rol = $_SESSION['user_id_role'] ?? null;
if (!isset($_SESSION['user_id_rol'])) {
    header("Location: view/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Menu -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
    <!-- Carrousel -->
    <div id="carouselExampleCaptions" class="carousel slide mb-4 custom-carousel" data-bs-ride="carousel"
        data-bs-interval="3000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/carousel1.jpg" class="d-block w-100 h-100" style="object-fit: cover;" alt="...">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>LAS MEJORES VERDURAS</h5>
                    <p>Encuentra aqui, las mejores verduras del mercado.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/carousel2.jpg" class="d-block w-100 h-100" style="object-fit: cover;" alt="...">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>LO MEJOR DEL CAMPO</h5>
                    <p>Solo los mejores productos para nuestros usuarios.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/carousel3.jpg" class="d-block w-100 h-100" style="object-fit: cover;" alt="...">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>LAS FRUTAS MAS FRESCAS</h5>
                    
                    <p>Mira las ultimas publicaciones en frutas.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Apartado de productos -->
    <?php include 'config/conexion.php'; ?>

    <section class="container mt-5 productos-fondo">
        <h2 class="text-center mb-4 fw-bold display-6 border-bottom pb-2" style="letter-spacing:1px;">Productos
            Publicados</h2>
        <div class="row g-4 justify-content-center">
            <?php
            $stmt = $pdo->query("SELECT * FROM productos ORDER BY fecha_publicacion DESC");
            while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch">
                    <a href="view/producto_detalle.php?id=<?php echo $producto['id']; ?>"
                        class="w-100 text-decoration-none text-dark">
                        <div class="card h-100 border-0 shadow-sm minimal-card">
                            <img src="img/<?php echo htmlspecialchars($producto['imagen']); ?>"
                                class="card-img-top rounded-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <h5 class="card-title mb-2 fw-semibold text-truncate">
                                    <?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                <p class="card-text small text-muted mb-2" style="min-height:48px;">
                                    <?php echo htmlspecialchars($producto['descripcion']); ?>
                                </p>
                                <p class="card-text mb-0"><span
                                        class="fw-bold text-success">$<?php echo number_format($producto['precio']); ?></span>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </section>
    <!-- Apartado de productos por categoría -->
    <section class="container mt-5">
        <h2 class="text-center mb-4 fw-bold display-6 border-bottom pb-2" style="letter-spacing:1px;">Productos por
            Categoría</h2>
        <?php
        // Consulta para obtener las categorías desde la tabla `categoria`
        $categoriasStmt = $pdo->query("SELECT id_categoria, nombre FROM categoria ORDER BY nombre ASC");
        $categorias = $categoriasStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($categorias as $categoria) {
            $categoriaNombre = htmlspecialchars($categoria['nombre']);
            $categoriaId = $categoria['id_categoria'];
            ?>
            <div class="mb-5">
                <h3 class="text-success border-start border-4 ps-3 mb-4" style="font-weight:600; letter-spacing:0.5px;">
                    <?php echo $categoriaNombre; ?> </h3>
                <div class="row g-4 justify-content-center">
                    <?php
                    // Consulta para obtener los productos de la categoría actual
                    $productosStmt = $pdo->prepare("SELECT * FROM productos WHERE id_categoria = ? ORDER BY fecha_publicacion DESC");
                    $productosStmt->execute([$categoriaId]);
                    while ($producto = $productosStmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch">
                            <a href="view/producto_detalle.php?id=<?php echo $producto['id_producto']; ?>"
                                class="w-100 text-decoration-none text-dark">
                                <div class="card h-100 border-0 shadow-sm minimal-card">
                                    <img src="img/<?php echo htmlspecialchars($producto['foto']); ?>"
                                        class="card-img-top rounded-top"
                                        alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <h5 class="card-title mb-2 fw-semibold text-truncate">
                                            <?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                        <p class="card-text small text-muted mb-2" style="min-height:48px;">
                                            <?php echo htmlspecialchars($producto['descripcion']); ?>
                                        </p>
                                        <p class="card-text mb-0"><span
                                                class="fw-bold text-success">$<?php echo number_format($producto['precio_unitario']); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </section>
    </div>
    <?php include 'config/conexion.php'; ?>
    <div class="container mt-5">

    </div>

    <footer class="bg-light text-center py-3">
        <p class="mb-0">&copy; 2025 Plaza Móvil. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
</body>

</html>