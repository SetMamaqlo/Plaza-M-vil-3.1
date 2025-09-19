<?php
session_start();
if (!isset($_SESSION['pagos'])) {
    $_SESSION['pagos'] = [];
}

// ============ FUNCIONES CRUD (SIMULACIÓN DE BASE DE DATOS) ============

function crearPago($idPedido, $monto, $metodo, $estado, $transaccionId, $nombreCliente) {
    $nuevoPago = [
        'id_pago' => count($_SESSION['pagos']) + 1,
        'id_pedido' => $idPedido,
        'monto' => $monto,
        'metodo' => $metodo,
        'estado' => $estado,
        'transaccion_id' => $transaccionId,
        'nombre_cliente' => $nombreCliente // <-- Aquí se guarda el nombre del cliente
    ];
    $_SESSION['pagos'][] = $nuevoPago;
}

function obtenerPagos() {
    return $_SESSION['pagos'];
}

function obtenerPagoPorId($id) {
    foreach ($_SESSION['pagos'] as $pago) {
        if ($pago['id_pago'] == $id) {
            return $pago;
        }
    }
    return null;
}

function editarPago($id, $nuevoEstado) {
    foreach ($_SESSION['pagos'] as $key => $pago) {
        if ($pago['id_pago'] == $id) {
            $_SESSION['pagos'][$key]['estado'] = $nuevoEstado;
            return true;
        }
    }
    return false;
}

function eliminarPago($id) {
    foreach ($_SESSION['pagos'] as $key => $pago) {
        if ($pago['id_pago'] == $id) {
            unset($_SESSION['pagos'][$key]);
            $_SESSION['pagos'] = array_values($_SESSION['pagos']); // Reorganiza los índices
            return true;
        }
    }
    return false;
}

// ============ LÓGICA DE PROCESAMIENTO DE ACCIONES ============

$mensaje = "";
if (isset($_GET['estado'])) {
    if ($_GET['estado'] == 'exitoso') {
        $mensaje = "<p class='success'>✅ ¡Pago exitoso! ID de transacción: " . htmlspecialchars($_GET['transaccion_id']) . "</p>";
    } else {
        $mensaje = "<p class='error'>❌ El pago no pudo ser procesado. Por favor, intente de nuevo.</p>";
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'editar' && isset($_POST['id_pago']) && isset($_POST['estado'])) {
        if (editarPago($_POST['id_pago'], $_POST['estado'])) {
            $mensaje = "<p class='success'>✅ Pago #" . htmlspecialchars($_POST['id_pago']) . " actualizado correctamente.</p>";
        }
    }
    if ($_POST['action'] == 'eliminar' && isset($_POST['id_pago'])) {
        if (eliminarPago($_POST['id_pago'])) {
            $mensaje = "<p class='success'>🗑️ Pago #" . htmlspecialchars($_POST['id_pago']) . " eliminado correctamente.</p>";
        }
    }
}

$pagos = obtenerPagos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pagos</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; margin: 2em; }
        .container { background: white; padding: 2em; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 1em; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        form.actions { display: inline; margin-right: 5px; } /* Añadido margin para separar botones */
        .btn { padding: 8px 12px; border-radius: 4px; text-decoration: none; color: white; border: none; cursor: pointer; margin-top: 5px; /* Espacio entre botones */ }
        .btn-edit { background-color: #ffc107; }
        .btn-delete { background-color: #dc3545; }
        .btn-back { background-color: #6c757d; }
        .btn-pdf { background-color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestión de Pagos</h2>
        <?php echo $mensaje; ?>
        <a href="index.html" class="btn btn-back">Volver a Pagar</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID Pago</th>
                    <th>ID Pedido</th>
                    <th>Monto</th>
                    <th>Método</th>
                    <th>Cliente</th> <th>Estado</th>
                    <th>ID Transacción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagos as $pago): ?>
                <tr>
                    <td><?php echo htmlspecialchars($pago['id_pago']); ?></td>
                    <td><?php echo htmlspecialchars($pago['id_pedido']); ?></td>
                    <td>$<?php echo htmlspecialchars($pago['monto']); ?></td>
                    <td><?php echo htmlspecialchars($pago['metodo']); ?></td>
                    <td><?php echo htmlspecialchars($pago['nombre_cliente'] ?? 'N/D'); ?></td> <td><?php echo htmlspecialchars($pago['estado']); ?></td>
                    <td><?php echo htmlspecialchars($pago['transaccion_id']); ?></td>
                    <td>
                        <form class="actions" method="post" action="gestion_pagos.php">
                            <input type="hidden" name="action" value="editar">
                            <input type="hidden" name="id_pago" value="<?php echo htmlspecialchars($pago['id_pago']); ?>">
                            <select name="estado" onchange="this.form.submit()">
                                <option value="Completado" <?php echo ($pago['estado'] == 'Completado') ? 'selected' : ''; ?>>Completado</option>
                                <option value="Fallido" <?php echo ($pago['estado'] == 'Fallido') ? 'selected' : ''; ?>>Fallido</option>
                                <option value="Pendiente" <?php echo ($pago['estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                            </select>
                        </form>
                        <form class="actions" method="post" action="gestion_pagos.php" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este pago?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_pago" value="<?php echo htmlspecialchars($pago['id_pago']); ?>">
                            <button type="submit" class="btn btn-delete">Eliminar</button>
                        </form>
                        <a href="generar_pdf.php?id_pago=<?php echo $pago['id_pago']; ?>" 
                           class="btn btn-pdf" target="_blank">📄 PDF</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>