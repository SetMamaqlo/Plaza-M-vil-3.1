<?php
require_once '../config/conexion.php';
session_start();

// Verificar si hay usuario logueado
if (!isset($_SESSION['user_id_usuario'])) {
    header("Location: login.php");
    exit;
}

// Opcional: cargar productos si el cliente va a pagar un pedido específico
$stmt = $pdo->query("SELECT id_producto, nombre FROM productos ORDER BY nombre ASC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagos - Plaza Móvil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-success {
            background-color: #2E7D32;
            border: none;
        }
        .btn-success:hover {
            background-color: #256428;
        }
        .payment-icons img {
            width: 60px;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <?php include '../navbar.php'; ?>
    <div style="height:70px"></div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4">
                    <h2 class="text-center mb-4 text-success">Realizar Pago</h2>
                    <form action="../controller/procesar_pago.php" method="POST">
                        <div class="mb-3">
                            <label for="id_pedido" class="form-label">ID Pedido</label>
                            <input type="text" class="form-control" id="id_pedido" name="id_pedido" placeholder="Ej: PED-12345" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_producto" class="form-label">Producto</label>
                            <select class="form-select" id="id_producto" name="id_producto" required>
                                <option value="" disabled selected>Selecciona un producto</option>
                                <?php foreach ($productos as $p): ?>
                                    <option value="<?= $p['id_producto'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="monto_total" class="form-label">Monto Total ($)</label>
                            <input type="number" class="form-control" id="monto_total" name="monto_total" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Método de Pago</label>
                            <div class="d-flex justify-content-center payment-icons">
                                <label class="me-3">
                                    <input type="radio" name="metodo" value="Tarjeta" required>
                                    <img src="../img/tarjeta.png" alt="Tarjeta">
                                </label>
                                <label class="me-3">
                                    <input type="radio" name="metodo" value="PSE" required>
                                    <img src="../img/pse.png" alt="PSE">
                                </label>
                                <label>
                                    <input type="radio" name="metodo" value="Nequi" required>
                                    <img src="../img/nequi.png" alt="Nequi">
                                </label>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-credit-card"></i> Confirmar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5 mb-3 text-muted">
        <p>&copy; <?= date('Y') ?> Plaza Móvil. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>