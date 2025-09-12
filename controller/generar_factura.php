<?php
require_once '../config/conexion.php';
require_once 'generar_pdf.php';

if (!isset($_GET['id_pago'])) {
    die("Error: No se proporcionó el ID de pago.");
}

$id_pago = $_GET['id_pago'];

// Consulta para unir cliente, agricultor y producto
$stmt = $pdo->prepare("
    SELECT 
        p.id_pago, p.id_pedido, p.monto_total, p.metodo, p.fecha_pago,
        u.nombre_completo AS cliente, u.email AS cliente_email, u.telefono AS cliente_telefono,
        agri_user.nombre_completo AS agricultor, agri_user.email AS agricultor_email, agri_user.telefono AS agricultor_telefono,
        prod.nombre AS producto, prod.precio_unitario, 1 AS cantidad
    FROM pagos p
    JOIN usuarios u ON u.id_usuario = p.id_cliente
    JOIN productos prod ON prod.id_producto = p.id_producto
    JOIN agricultor agri ON agri.id_agricultor = prod.id_agricultor
    JOIN usuarios agri_user ON agri_user.id_usuario = agri.id_usuario
    WHERE p.id_pago = ?
");
$stmt->execute([$id_pago]);
$factura = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$factura) {
    die("Factura no encontrada.");
}

generarPDF($factura);
?>