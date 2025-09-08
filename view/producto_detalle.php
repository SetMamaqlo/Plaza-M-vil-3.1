<?php
require_once '../config/conexion.php';
session_start();
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar un producto al carrito
$id_producto = $_POST['id_producto'] ?? null;
if ($id_producto) {
    $_SESSION['carrito'][] = $id_producto;
}

// Verificar si se ha pasado un ID de producto
if (!isset($_GET['id'])) {
    echo "Producto no encontrado.";
    exit;
}

// Obtener el ID del producto
$id_producto = $_GET['id'];

// Consultar los detalles del producto y del vendedor
$stmt = $pdo->prepare("SELECT p.*, u.nombre_completo AS vendedor, u.telefono, u.foto 
                       FROM productos p 
                       JOIN usuarios u ON p.id_agricultor = u.id_usuario 
                       WHERE p.id_producto = ?");
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el producto existe
if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}

// Obtener el rol del usuario si está logueado
$role = $_SESSION['user_role'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre']); ?> - Detalles</title>
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
        <div class="row">
            <div class="container mt-5">
                <div class="row">
                </div>
                <!-- Detalles del producto y vendedor -->
                <div class="container mt-5">
                    <div class="row">
                        <!-- Imagen del producto -->
                        <div class="col-md-6 d-flex align-items-center justify-content-center">
                            <img src="../img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="product-image"
                                alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        </div>
                        <!-- Detalles del producto y vendedor -->
                        <div class="col-md-6">
                            <div class="product-details">
                                <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                                <p><strong>Descripción:</strong>
                                    <?php echo htmlspecialchars($producto['descripcion']); ?></p>
                                <p><strong>Precio:</strong> $<?php echo number_format($producto['precio']); ?></p>
                                <p><strong>Fecha de publicación:</strong>
                                    <?php echo htmlspecialchars($producto['fecha_publicacion']); ?></p>
                                <hr>
                                <div class="d-flex align-items-center mb-3">
                                    <?php if (!empty($producto['foto'])): ?>
                                        <img src="../img/<?php echo htmlspecialchars($producto['foto']); ?>"
                                            alt="Foto de perfil"
                                            style="width:50px; height:50px; object-fit:cover; border-radius:50%; margin-right:15px;">
                                    <?php else: ?>
                                        <i class="bi bi-person-circle"
                                            style="font-size: 2.5rem; color: #6c757d; margin-right:15px;"></i>
                                    <?php endif; ?>
                                    <div>
                                        <span
                                            class="fw-bold"><?php echo htmlspecialchars($producto['vendedor']); ?></span><br>
                                        <small class="text-muted"><i class="bi bi-telephone"></i>
                                            <?php echo htmlspecialchars($producto['telefono']); ?></small>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <form action="../controller/procesar_compra.php" method="POST" class="mt-3">
                                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                <button type="submit" class="btn btn-outline-success w-100 mb-3">Comprar Ahora</button>
                                </form>
                                <form action="../controller/carritocontroller.php" method="POST">
                                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                    <button type="submit" class="btn btn-success w-100">Añadir al Carrito</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container mt-5">
                    <h4 class="mb-4 text-center">Productos recomendados</h4>
                    <div class="row">
                        <?php
                        // Consulta para obtener 3 productos aleatorios, excluyendo el actual
                        $stmtRecomendados = $pdo->prepare("SELECT id_producto, nombre, imagen, precio FROM productos WHERE id_producto != ? ORDER BY RAND() LIMIT 3");
                        $stmtRecomendados->execute([$producto['id_producto']]);
                        $recomendados = $stmtRecomendados->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($recomendados as $reco): ?>
                            <div class="col-md-4 mb-4">
                                <a href="producto_detalle.php?id=<?php echo $reco['id_producto']; ?>"
                                    style="text-decoration:none; color:inherit;">
                                    <div class="card h-100 shadow-sm">
                                        <img src="../img/<?php echo htmlspecialchars($reco['imagen']); ?>"
                                            class="card-img-top" style="height:200px; object-fit:cover;"
                                            alt="<?php echo htmlspecialchars($reco['nombre']); ?>">
                                        <div class="card-body text-center">
                                            <h5 class="card-title"><?php echo htmlspecialchars($reco['nombre']); ?></h5>
                                            <p class="card-text text-success fw-bold">
                                                $<?php echo number_format($reco['precio']); ?></p>
                                            <a href="producto_detalle.php?id=<?php echo $reco['id_producto']; ?>"
                                                class="btn btn-outline-primary btn-sm">Ver Detalles</a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <footer class="bg-light text-center py-3 mt-5">
                    <p class="mb-0">&copy; 2025 Plaza Móvil. Todos los derechos reservados.</p>
                </footer>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>