<?php
require_once '../config/conexion.php';
require_once '../pasarela_final/pasarela_final/fpdf186/fpdf.php';

if (!isset($_GET['id_pago'])) {
    die("No se especificó el pago.");
}

$id_pago = $_GET['id_pago'];
$stmt = $pdo->prepare("SELECT p.*, u.nombre_completo AS cliente FROM pagos p JOIN usuarios u ON u.id_usuario = p.id_cliente WHERE p.id_pago = ?");
$stmt->execute([$id_pago]);
$pago = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pago) {
    die("Pago no encontrado.");
}

$pdf = new FPDF();
$pdf->AddPage();

$logo_path = '../img/logoplaza_movil.png';
if (file_exists($logo_path)) {
    $pdf->Image($logo_path, 10, 8, 30);
}

$pdf->SetFont('Arial', 'B', 16);
$pdf->SetXY(40, 8);
$pdf->Cell(0, 10, 'Factura de Pago', 0, 1, 'C');
$pdf->Ln(20);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'ID Pago:', 0, 0);
$pdf->Cell(40, 10, $pago['id_pago'], 0, 1);
$pdf->Cell(40, 10, 'Cliente:', 0, 0);
$pdf->Cell(40, 10, utf8_decode($pago['cliente']), 0, 1);
$pdf->Cell(40, 10, 'Monto:', 0, 0);
$pdf->Cell(40, 10, "$" . number_format($pago['monto_total'], 2), 0, 1);
$pdf->Cell(40, 10, 'Método:', 0, 0);
$pdf->Cell(40, 10, utf8_decode($pago['metodo']), 0, 1);
$pdf->Cell(40, 10, 'Estado:', 0, 0);
$pdf->Cell(40, 10, $pago['estado'], 0, 1);
$pdf->Cell(40, 10, 'Transacción:', 0, 0);
$pdf->Cell(40, 10, $pago['transaccion_id'], 0, 1);
$pdf->Cell(40, 10, 'Fecha:', 0, 0);
$pdf->Cell(40, 10, $pago['fecha_pago'], 0, 1);

$pdf->Output("I", "factura_pago_{$pago['id_pago']}.pdf");
?>