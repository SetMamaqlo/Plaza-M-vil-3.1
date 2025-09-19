<?php
// Inicia la sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica que haya pagos en la sesión
if (!isset($_SESSION['pagos']) || empty($_SESSION['pagos'])) {
    die("⚠️ No hay datos de pagos disponibles para generar el PDF.");
}

// Incluye la librería FPDF
require_once __DIR__ . '/fpdf186/fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();

// --- Añadir Logo de la empresa ---
// La ruta debe ser relativa al script que está siendo ejecutado.
// Asegúrate de que el archivo se llama 'logoplaza_movil.png' y está en la carpeta 'img'.
$logo_path = __DIR__ . '/img/logoplaza_movil.png';
if (file_exists($logo_path)) {
    $pdf->Image($logo_path, 10, 8, 30); // Ruta corregida, X, Y, Ancho
}

// Título
$pdf->SetFont('Arial', 'B', 16);
// Ajustamos el espacio para que el título no choque con el logo
$pdf->SetXY(40, 8); // X=40 para empezar después del logo, Y=8 para la misma línea del logo
$pdf->Cell(0, 10, 'Reporte de Pagos', 0, 1, 'C');
$pdf->Ln(20); // Salto de línea para dar espacio después del título/logo

// --- Encabezados de tabla ---
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'ID', 1, 0, 'C');
$pdf->Cell(40, 10, 'Cliente', 1, 0, 'C'); // Nueva columna para Cliente
$pdf->Cell(30, 10, 'Pedido', 1, 0, 'C');
$pdf->Cell(30, 10, 'Monto', 1, 0, 'C');
$pdf->Cell(30, 10, 'Metodo', 1, 0, 'C');
$pdf->Cell(30, 10, 'Estado', 1, 0, 'C');
$pdf->Cell(40, 10, 'Transaccion', 1, 1, 'C'); // El '1' al final mueve a la siguiente línea

// --- Datos de la tabla ---
$pdf->SetFont('Arial', '', 11);

// Ordenar los pagos por ID de forma ascendente para que aparezcan en orden
usort($_SESSION['pagos'], function($a, $b) {
    return $a['id_pago'] <=> $b['id_pago'];
});

foreach ($_SESSION['pagos'] as $pago) {
    // Asegurarse de que el nombre del cliente exista, de lo contrario, mostrar 'N/D'
    $nombre_cliente = isset($pago['nombre_cliente']) ? $pago['nombre_cliente'] : 'N/D';

    $pdf->Cell(20, 10, $pago['id_pago'], 1, 0, 'C');
    $pdf->Cell(40, 10, $nombre_cliente, 1, 0, 'C');
    $pdf->Cell(30, 10, "$" . $pago['monto'], 1, 0, 'C');
    // Usar utf8_decode para caracteres especiales en el método
    $pdf->Cell(30, 10, utf8_decode($pago['metodo']), 1, 0, 'C');
    $pdf->Cell(30, 10, $pago['estado'], 1, 0, 'C');
    $pdf->Cell(40, 10, $pago['transaccion_id'], 1, 1, 'C'); // El '1' al final mueve a la siguiente línea
}

// Salida del PDF
$pdf->Output("I", "reporte_pagos.pdf");
?>