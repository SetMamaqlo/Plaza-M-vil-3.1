<?php
session_start();
if (!isset($_SESSION['user_id_usuario'])) {
    header('Location: login.php');
    exit();
}
require_once '../model/pedido_model.php';

$id_pedido = isset($_GET['id_pedido']) ? $_GET['id_pedido'] : null;
if (!$id_pedido) {
    echo "ID de pedido no especificado.";
    exit;
}

$pedido = PedidoModel::obtenerPedido($id_pedido);
if (!$pedido) {
    echo "Pedido no encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_estado = $_POST['estado'];
    PedidoModel::actualizarEstado($id_pedido, $nuevo_estado);
    header("Location: historialpedidos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar.php'; ?>
    <div class="container mt-5">
        <h2>Editar Estado del Pedido #<?php echo htmlspecialchars($id_pedido); ?></h2>
        <form method="POST">
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="pendiente" <?php if($pedido['estado']=='pendiente') echo 'selected'; ?>>Pendiente</option>
                    <option value="procesado" <?php if($pedido['estado']=='procesado') echo 'selected'; ?>>Procesado</option>
                    <option value="enviado" <?php if($pedido['estado']=='enviado') echo 'selected'; ?>>Enviado</option>
                    <option value="entregado" <?php if($pedido['estado']=='entregado') echo 'selected'; ?>>Entregado</option>
                    <option value="cancelado" <?php if($pedido['estado']=='cancelado') echo 'selected'; ?>>Cancelado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="../view/historialpedidos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
