<?php
session_start();

require_once '../config/conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Obtener los IDs de productos en el carrito
$carrito = $_SESSION['carrito'] ?? []; // Asegúrate de que sea un array
$productos = [];

if (!empty($carrito) && is_array($carrito)) { // Verifica que $carrito sea un array
    // Prepara la consulta para obtener los productos del carrito
    $placeholders = implode(',', array_fill(0, count($carrito), '?'));
    try {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
        $stmt->execute($carrito);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
        $productos = [];
    }
} else {
    $productos = []; // Si $carrito no es válido, inicializa $productos como un array vacío
}

// Calcular el total del carrito
$total = 0;
foreach ($productos as $producto) {
    $total += $producto['precio'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navbar -->
    <?php include '../navbar.php'; ?>

    <!-- Espacio para que el contenido no quede oculto bajo la navbar fija -->
    <div style="height:70px"></div>

    <div class="cart-container">
        <div class="d-flex align-items-center mb-4">
            <i class="bi bi-cart3 cart-icon"></i>
            <h2 class="ms-3 cart-title mb-0">Carrito de Compras</h2>
        </div>
        <?php if (empty($productos)): ?>
            <div class="cart-empty">
                <i class="bi bi-emoji-frown cart-empty-icon"></i>
                <p>Tu carrito está vacío.</p>
                <a href="../index.php" class="btn btn-outline-success mt-2"><i class="bi bi-arrow-left"></i> Seguir
                    comprando</a>
            </div>
        <?php else: ?>
            <?php foreach ($productos as $producto): ?>
                <div class="row align-items-center cart-product">
                    <div class="col-2">
                        <img src="../img/<?php echo htmlspecialchars($producto['imagen']); ?>" class="cart-img"
                            alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    </div>
                    <div class="col-6">
                        <div class="fw-bold"><?php echo htmlspecialchars($producto['nombre']); ?></div>
                        <div class="text-muted"><?php echo htmlspecialchars($producto['descripcion']); ?></div>
                    </div>
                    <div class="col-2 text-end">
                        <span class="text-success fw-semibold">$<?php echo number_format($producto['precio']); ?></span>
                    </div>
                    <div class="col-2 text-end">
                        <form action="eliminar_del_carrito.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
                            <button type="submit" class="btn btn-link cart-remove" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <span class="cart-total">Total: $<?php echo number_format($total); ?></span>
                <a href="../controller/procesar_compra.php" class="btn btn-checkout btn-lg text-white">
                    <i class="bi bi-credit-card"></i> Comprar ahora
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>