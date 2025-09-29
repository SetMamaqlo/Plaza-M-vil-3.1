<?php
require_once '../config/conexion.php';
require_once '../fpdf186/fpdf.php';

session_start();

$id_pedido = $_GET['id_pedido'] ?? null;

if (!$id_pedido) {
    die("No se recibió un pedido válido.");
}

// ✅ Traer info del pedido y usuario
$stmt = $pdo->prepare("
    SELECT u.nombre AS cliente, u.email, p.fecha, p.id_pedido
    FROM pedidos p
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    WHERE p.id_pedido = ?
");
$stmt->execute([$id_pedido]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("Pedido no encontrado.");
}

// ✅ Traer detalles del pedido
$stmt = $pdo->prepare("
    SELECT pr.nombre, pr.precio_unitario, pd.cantidad
    FROM pedido_detalle pd
    JOIN productos pr ON pd.id_producto = pr.id_producto
    WHERE pd.id_pedido = ?
");
$stmt->execute([$id_pedido]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Generar PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Encabezado
$pdf->Cell(0, 10, 'Factura de Compra', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Cliente: ' . $pedido['cliente'], 0, 1);
$pdf->Cell(0, 10, 'Correo: ' . $pedido['email'], 0, 1);
$pdf->Cell(0, 10, 'Fecha: ' . $pedido['fecha'], 0, 1);
$pdf->Ln(10);

// Tabla de productos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Producto', 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Cell(40, 10, 'Precio Unit.', 1);
$pdf->Cell(40, 10, 'Subtotal', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$total = 0;
foreach ($productos as $prod) {
    $subtotal = $prod['cantidad'] * $prod['precio_unitario'];
    $total += $subtotal;

    $pdf->Cell(80, 10, $prod['nombre'], 1);
    $pdf->Cell(30, 10, $prod['cantidad'], 1, 0, 'C');
    $pdf->Cell(40, 10, number_format($prod['precio_unitario'], 2), 1, 0, 'R');
    $pdf->Cell(40, 10, number_format($subtotal, 2), 1, 0, 'R');
    $pdf->Ln();
}

// Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(150, 10, 'TOTAL', 1);
$pdf->Cell(40, 10, number_format($total, 2), 1, 0, 'R');

// ✅ Descargar directo
$pdf->Output("D", "Factura_Pedido_$id_pedido.pdf");
exit;
