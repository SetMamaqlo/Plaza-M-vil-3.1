<?php
require_once '../config/conexion.php';
require_once '../fpdf186/fpdf.php';

session_start();

$pref_id = $_GET['pref_id'] ?? null;
if (!$pref_id) {
    die("No se encontró la preferencia.");
}

// ✅ Traer datos del pago y pedido
$stmt = $pdo->prepare("
    SELECT pg.*, p.id_pedido, u.nombre AS cliente
    FROM pagos pg
    JOIN pedidos p ON pg.id_pedido = p.id_pedido
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    WHERE pg.transaccion_id = ?
");
$stmt->execute([$pref_id]);
$pago = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pago) {
    die("No se encontró el pago.");
}

// ✅ Traer productos
$stmt = $pdo->prepare("
    SELECT pd.*, pr.nombre, pr.precio_unitario
    FROM pedido_detalle pd
    JOIN productos pr ON pd.id_producto = pr.id_producto
    WHERE pd.id_pedido = ?
");
$stmt->execute([$pago['id_pedido']]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Generar PDF
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Factura de Compra',0,1,'C');

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Cliente: ' . $pago['cliente'],0,1);
$pdf->Cell(0,10,'Pedido: ' . $pago['id_pedido'],0,1);
$pdf->Cell(0,10,'Transaccion: ' . $pago['transaccion_id'],0,1);
$pdf->Cell(0,10,'Estado: ' . $pago['estado'],0,1);

// Tabla productos
$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,10,'Producto',1);
$pdf->Cell(30,10,'Cantidad',1);
$pdf->Cell(40,10,'Precio Unitario',1);
$pdf->Cell(40,10,'Subtotal',1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);
$total = 0;
foreach ($productos as $prod) {
    $subtotal = $prod['cantidad'] * $prod['precio_unitario'];
    $pdf->Cell(80,10,$prod['nombre'],1);
    $pdf->Cell(30,10,$prod['cantidad'],1,0,'C');
    $pdf->Cell(40,10,"$".number_format($prod['precio_unitario'],0,',','.'),1,0,'R');
    $pdf->Cell(40,10,"$".number_format($subtotal,0,',','.'),1,0,'R');
    $pdf->Ln();
    $total += $subtotal;
}

$pdf->SetFont('Arial','B',12);
$pdf->Cell(150,10,'Total',1);
$pdf->Cell(40,10,"$".number_format($total,0,',','.'),1,0,'R');

// ✅ Descargar PDF
$pdf->Output('D', 'factura_'.$pago['id_pedido'].'.pdf');
exit;
