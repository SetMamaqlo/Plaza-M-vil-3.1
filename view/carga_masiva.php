<?php
// Verificar si la sesión ya está iniciada antes de iniciarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id_usuario']) || $_SESSION['user_id_rol'] != 3) {
    header('Location: login.php');
    exit();
}

require_once '../config/conexion.php';

$id_agricultor = $_SESSION['user_id_usuario'];
$mensaje = '';
$tipoMensaje = '';
$debug_info = '';

// Procesar el archivo CSV cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel'])) {
    try {
        // Validar archivo
        if ($_FILES['archivo_excel']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir el archivo: ' . $_FILES['archivo_excel']['error']);
        }

        $extension = strtolower(pathinfo($_FILES['archivo_excel']['name'], PATHINFO_EXTENSION));
        if ($extension !== 'csv') {
            throw new Exception('Solo se permiten archivos CSV (.csv)');
        }

        if ($_FILES['archivo_excel']['size'] > 10 * 1024 * 1024) {
            throw new Exception('El archivo es demasiado grande. Máximo 10MB permitido.');
        }

        $archivoTemp = $_FILES['archivo_excel']['tmp_name'];
        
        $debug_info .= "Archivo subido: " . $_FILES['archivo_excel']['name'] . "<br>";
        $debug_info .= "Tamaño: " . $_FILES['archivo_excel']['size'] . " bytes<br>";
        
        if (!file_exists($archivoTemp)) {
            throw new Exception('El archivo temporal no existe');
        }
        
        $productos = procesarCSV($archivoTemp, $pdo, $id_agricultor, $debug_info);
        
        $debug_info .= "Productos encontrados: " . count($productos) . "<br>";
        
        if (count($productos) > 0) {
            $resultados = insertarProductos($productos, $pdo, $id_agricultor);
            
            $mensaje = "Carga masiva completada: " . 
                       $resultados['insertados'] . " productos insertados, " . 
                       $resultados['actualizados'] . " actualizados, " . 
                       $resultados['errores'] . " errores";
            $tipoMensaje = $resultados['errores'] > 0 ? 'warning' : 'success';
        } else {
            $mensaje = "No se encontraron productos válidos en el archivo CSV.";
            $tipoMensaje = 'warning';
        }

    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
        $tipoMensaje = 'danger';
    }
}

// Función para procesar archivos CSV
function procesarCSV($archivoTemp, $pdo, $id_agricultor, &$debug_info) {
    $productos = [];
    $fila = 0;
    
    $debug_info .= "Abriendo archivo CSV...<br>";
    
    if (($handle = fopen($archivoTemp, "r")) !== FALSE) {
        $debug_info .= "Archivo abierto correctamente<br>";
        
        // Leer encabezados
        $encabezados = fgetcsv($handle, 1000, ",");
        $debug_info .= "Encabezados: " . implode(" | ", $encabezados) . "<br>";
        $debug_info .= "Número de columnas: " . count($encabezados) . "<br>";
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $fila++;
            
            // Saltar filas vacías
            if (count($data) == 1 && empty(trim($data[0]))) {
                continue;
            }
            
            $debug_info .= "Fila $fila: " . implode(" | ", $data) . "<br>";
            $debug_info .= "Columnas en fila $fila: " . count($data) . "<br>";
            
            // Limpiar datos
            $data = array_map('trim', $data);
            $data = array_filter($data, function($value) { 
                return $value !== '' && $value !== null; 
            });
            
            $debug_info .= "Datos limpios: " . implode(" | ", $data) . "<br>";
            
            // Solo necesitamos 5 columnas mínimas, la 6ta (Unidad) es opcional
            if (count($data) >= 5) {
                $producto = validarFilaProducto($data, $fila, $pdo, $id_agricultor, $debug_info);
                if ($producto) {
                    $productos[] = $producto;
                    $debug_info .= "✅ Producto válido: " . $producto['nombre'] . "<br>";
                } else {
                    $debug_info .= "❌ Producto inválido<br>";
                }
            } else {
                $debug_info .= "❌ Fila $fila: Solo tiene " . count($data) . " columnas, se necesitan al menos 5<br>";
            }
        }
        fclose($handle);
    }
    
    return $productos;
}

// Función para validar una fila de producto
function validarFilaProducto($data, $fila, $pdo, $id_agricultor, &$debug_info) {
    if (count($data) < 5) {
        $debug_info .= "❌ Fila $fila: Solo tiene " . count($data) . " columnas<br>";
        return null;
    }
    
    $producto = [
        'nombre' => trim($data[0]),
        'descripcion' => trim($data[1]),
        'id_categoria' => obtenerIdCategoria(trim($data[2]), $pdo, $debug_info),
        'precio_unitario' => floatval(str_replace(['$', ','], '', $data[3])),
        'stock' => intval($data[4]),
        // El campo unidad es opcional (columna 6)
        'unidad' => isset($data[5]) ? trim($data[5]) : 'Unidad'
    ];
    
    $debug_info .= "📊 Producto - Nombre: '{$producto['nombre']}', Precio: {$producto['precio_unitario']}, Stock: {$producto['stock']}, Categoría: {$data[2]}<br>";
    
    // Validaciones básicas
    if (empty($producto['nombre'])) {
        $debug_info .= "❌ Nombre vacío<br>";
        return null;
    }
    
    if ($producto['precio_unitario'] <= 0) {
        $debug_info .= "❌ Precio inválido: " . $producto['precio_unitario'] . "<br>";
        return null;
    }
    
    if ($producto['stock'] < 0) {
        $debug_info .= "❌ Stock inválido: " . $producto['stock'] . "<br>";
        return null;
    }
    
    if (!$producto['id_categoria']) {
        $debug_info .= "❌ Categoría no encontrada: '" . $data[2] . "'<br>";
        return null;
    }
    
    $debug_info .= "✅ Producto válido<br>";
    return $producto;
}

// Función para obtener ID de categoría por nombre
function obtenerIdCategoria($nombreCategoria, $pdo, &$debug_info) {
    if (empty($nombreCategoria)) {
        $debug_info .= "⚠️ Nombre de categoría vacío<br>";
        return null;
    }
    
    $debug_info .= "🔍 Buscando categoría: '$nombreCategoria'<br>";
    
    $stmt = $pdo->prepare("SELECT id_categoria, nombre FROM categoria WHERE nombre = ?");
    $stmt->execute([$nombreCategoria]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $debug_info .= "✅ Categoría encontrada: {$result['nombre']} (ID: {$result['id_categoria']})<br>";
    } else {
        $debug_info .= "❌ Categoría NO encontrada: '$nombreCategoria'<br>";
        
        // Mostrar categorías disponibles
        $stmt = $pdo->query("SELECT id_categoria, nombre FROM categoria ORDER BY nombre");
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $debug_info .= "📋 Categorías disponibles: ";
        foreach ($categorias as $cat) {
            $debug_info .= "{$cat['nombre']} (ID:{$cat['id_categoria']}), ";
        }
        $debug_info .= "<br>";
    }
    
    return $result ? $result['id_categoria'] : null;
}

// Función para insertar productos en la base de datos
function insertarProductos($productos, $pdo, $id_agricultor) {
    $resultados = [
        'insertados' => 0,
        'actualizados' => 0,
        'errores' => 0
    ];
    
    foreach ($productos as $producto) {
        try {
            // Verificar si el producto ya existe
            $stmt = $pdo->prepare("SELECT id_producto FROM productos WHERE nombre = ? AND id_agricultor = ?");
            $stmt->execute([$producto['nombre'], $id_agricultor]);
            $existente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existente) {
                // Actualizar producto existente
                $stmt = $pdo->prepare("
                    UPDATE productos SET 
                        descripcion = ?, id_categoria = ?, precio_unitario = ?, 
                        stock = stock + ?, fecha_publicacion = NOW()
                    WHERE id_producto = ?
                ");
                $stmt->execute([
                    $producto['descripcion'],
                    $producto['id_categoria'],
                    $producto['precio_unitario'],
                    $producto['stock'],
                    $existente['id_producto']
                ]);
                $resultados['actualizados']++;
            } else {
                // Insertar nuevo producto - SIN campo id_unidad
                $stmt = $pdo->prepare("
                    INSERT INTO productos (
                        id_agricultor, id_categoria, descripcion, nombre, 
                        stock, precio_unitario, fecha_publicacion
                    ) VALUES (?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([
                    $id_agricultor,
                    $producto['id_categoria'],
                    $producto['descripcion'],
                    $producto['nombre'],
                    $producto['stock'],
                    $producto['precio_unitario']
                ]);
                $resultados['insertados']++;
            }
        } catch (Exception $e) {
            error_log("Error insertando producto '{$producto['nombre']}': " . $e->getMessage());
            $resultados['errores']++;
        }
    }
    
    return $resultados;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga Masiva de Productos - Agricultor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/Plaza-M-vil-3.1/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/../navbar.php'; ?>
    <div style="height:70px"></div>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-upload"></i> Carga Masiva de Productos
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if (!empty($mensaje)): ?>
                            <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">
                                    <?php echo $tipoMensaje == 'success' ? '✅ Éxito' : ($tipoMensaje == 'warning' ? '⚠️ Advertencia' : '❌ Error'); ?>
                                </h5>
                                <?php echo htmlspecialchars($mensaje); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($debug_info)): ?>
                            <div class="alert alert-info">
                                <h5><i class="bi bi-bug"></i> Información de Depuración</h5>
                                <div style="max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                                    <?php echo nl2br(htmlspecialchars($debug_info)); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="alert alert-info">
                            <h5><i class="bi bi-info-circle"></i> Instrucciones:</h5>
                            <p>El archivo CSV debe tener este formato (la columna "Unidad" es opcional):</p>
                            
                            <div class="table-responsive mt-3">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Categoría</th>
                                            <th>Precio</th>
                                            <th>Stock</th>
                                            <th>Unidad (Opcional)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Manzana Roja</td>
                                            <td>Manzana fresca de la región</td>
                                            <td>Frutas</td>
                                            <td>2500</td>
                                            <td>100</td>
                                            <td>Kilo</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <form action="carga_masiva.php" method="POST" enctype="multipart/form-data" id="formCarga">
                            <div class="mb-4">
                                <label for="archivo_excel" class="form-label">
                                    <strong>Seleccionar archivo CSV:</strong>
                                </label>
                                <input type="file" class="form-control" id="archivo_excel" name="archivo_excel" 
                                       accept=".csv" required>
                                <div class="form-text">
                                    Tamaño máximo: 10MB. Formato permitido: CSV (.csv)
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="mis_productos.php" class="btn btn-secondary me-md-2">
                                    <i class="bi bi-arrow-left"></i> Volver a Mis Productos
                                </a>
                                <button type="submit" class="btn btn-success" id="btnCargar">
                                    <i class="bi bi-upload"></i> Iniciar Carga Masiva
                                </button>
                            </div>
                        </form>

                        <div class="mt-5">
                            <div class="alert alert-warning">
                                <h5><i class="bi bi-download"></i> ¿No tienes una plantilla?</h5>
                                <a href="../controller/descargar_plantilla.php?tipo=csv" 
                                   class="btn btn-outline-primary">
                                    <i class="bi bi-file-earmark-text"></i> Descargar Plantilla CSV
                                </a>
                            </div>
                        </div>

                        <!-- Información de categorías -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="bi bi-list-check"></i> Categorías Disponibles en tu Sistema</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $stmt = $pdo->query("SELECT id_categoria, nombre FROM categoria ORDER BY nombre");
                                        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        echo '<div class="small">';
                                        foreach ($categorias as $cat) {
                                            echo "<span class='badge bg-secondary me-1'>{$cat['nombre']} (ID:{$cat['id_categoria']})</span>";
                                        }
                                        echo '</div>';
                                        ?>
                                        <p class="mt-2 text-muted"><small>Usa exactamente estos nombres en la columna "Categoría" del CSV</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('formCarga').addEventListener('submit', function(e) {
            const archivo = document.getElementById('archivo_excel').files[0];
            const btnCargar = document.getElementById('btnCargar');
            
            if (archivo) {
                const fileName = archivo.name.toLowerCase();
                if (!fileName.endsWith('.csv')) {
                    e.preventDefault();
                    alert('Solo se permiten archivos CSV (.csv)');
                    return;
                }
                
                btnCargar.innerHTML = '<i class="bi bi-hourglass-split"></i> Procesando...';
                btnCargar.disabled = true;
            }
        });
    </script>
</body>
</html>