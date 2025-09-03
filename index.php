<?php
session_start();
$role = $_SESSION['user_role'] ?? null;
if (!isset($_SESSION['user_id'])) {
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
    <div id="carouselExampleCaptions" class="carousel slide mb-4 custom-carousel" data-bs-ride="carousel" data-bs-interval="3000">
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

    <div class="container mt-5 productos-fondo">
    <h2 class="text-center mb-4">Productos Publicados</h2>
    <div class="row"> <!-- Asegúrate de que las columnas estén dentro de un contenedor con la clase 'row' -->
        <?php
        $stmt = $pdo->query("SELECT * FROM productos ORDER BY fecha_publicacion DESC");
        while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="col-md-4 mb-4"> <!-- Divide en tres columnas por fila -->
                <a href="view/producto_detalle.php?id=<?php echo $producto['id']; ?>" style="text-decoration:none; color:inherit;">
                    <div class="card h-100 shadow-sm" style="cursor:pointer;">
                        <img src="img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top"
                            alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <p class="card-text"><strong>Precio:</strong>
                                $<?php echo number_format($producto['precio']); ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>

    <!-- Apartado de productos por categoría -->
<div class="container mt-5">
    <h2 class="text-center mb-4">Productos por Categoría</h2>
    <?php
    // Consulta para obtener las categorías
    $categoriasStmt = $pdo->query("SELECT DISTINCT categoria FROM productos ORDER BY categoria ASC");
    $categorias = $categoriasStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($categorias as $categoria) {
        $categoriaNombre = htmlspecialchars($categoria['categoria']);
        ?>
        <div class="mb-5">
            <h3 class="text-success"><?php echo $categoriaNombre; ?></h3>
            <div class="row">
                <?php
                // Consulta para obtener los productos de la categoría actual
                $productosStmt = $pdo->prepare("SELECT * FROM productos WHERE categoria = ? ORDER BY fecha_publicacion DESC");
                $productosStmt->execute([$categoriaNombre]);
                while ($producto = $productosStmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <a href="view/producto_detalle.php?id=<?php echo $producto['id']; ?>" style="text-decoration:none; color:inherit;">
                            <div class="card h-100 shadow-sm" style="cursor:pointer;">
                                <img src="img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top"
                                    alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                    <p class="card-text"><strong>Precio:</strong>
                                        $<?php echo number_format($producto['precio']); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
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