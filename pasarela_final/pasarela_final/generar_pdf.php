<?php
// Inicia sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['pagos']) || empty($_SESSION['pagos'])) {
    die("⚠️ No hay datos de pagos disponibles para generar el PDF.");
}

require_once __DIR__ . '/fpdf186/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();

// LOGO
$logo_path = __DIR__ . '/img/logoplaza_movil.png';
if (file_exists($logo_path)) {
    $pdf->Image($logo_path, 10, 8, 30);
}

// Título
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetXY(50, 8); // Posiciona el título a la derecha del logo
$pdf->Cell(0, 10, 'Reporte de Pagos', 0, 1, 'C');
$pdf->Ln(20);

// ENCABEZADOS DE TABLA
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'ID', 1, 0, 'C');
$pdf->Cell(35, 10, 'Cliente', 1, 0, 'C');
$pdf->Cell(25, 10, 'Pedido', 1, 0, 'C');
$pdf->Cell(25, 10, 'Monto', 1, 0, 'C');
$pdf->Cell(30, 10, 'Metodo', 1, 0, 'C');
$pdf->Cell(25, 10, 'Estado', 1, 0, 'C');
$pdf->Cell(45, 10, 'Transaccion', 1, 1, 'C');

// DATOS DE LA TABLA
$pdf->SetFont('Arial', '', 11);

// Ordena los pagos por ID para que se vean en orden
usort($_SESSION['pagos'], function($a, $b) {
    return $a['id_pago'] <=> $b['id_pago'];
});

foreach ($_SESSION['pagos'] as $pago) {
    $nombre_cliente = isset($pago['nombre_cliente']) ? $pago['nombre_cliente'] : 'N/D';
    $pdf->Cell(20, 10, $pago['id_pago'], 1, 0, 'C');
    $pdf->Cell(35, 10, utf8_decode($nombre_cliente), 1, 0, 'C');
    $pdf->Cell(25, 10, $pago['id_pedido'], 1, 0, 'C');
    $pdf->Cell(25, 10, "$" . $pago['monto'], 1, 0, 'C');
    $pdf->Cell(30, 10, utf8_decode($pago['metodo']), 1, 0, 'C');
    $pdf->Cell(25, 10, $pago['estado'], 1, 0, 'C');
    $pdf->Cell(45, 10, $pago['transaccion_id'], 1, 1, 'C');
}

$pdf->Output("I", "reporte_pagos.pdf");
?>