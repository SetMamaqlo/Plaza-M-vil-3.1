<?php
// Verificar si la sesión ya está iniciada antes de iniciarla
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id_usuario']) || $_SESSION['user_id_rol'] != 3) {
    header('Location: ../view/login.php');
    exit();
}

require_once '../config/conexion.php';

$id_agricultor = $_SESSION['user_id_usuario'];
$mensaje = '';
$tipoMensaje = ''; // success, danger, warning

// Procesar el archivo Excel cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel'])) {
    try {
        // Validar archivo
        if ($_FILES['archivo_excel']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir el archivo: ' . $_FILES['archivo_excel']['error']);
        }

        // Validar tipo de archivo
        $extension = strtolower(pathinfo($_FILES['archivo_excel']['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            throw new Exception('Solo se permiten archivos Excel (.xlsx, .xls) o CSV (.csv)');
        }

        // Mover archivo a directorio temporal
        $archivoTemp = $_FILES['archivo_excel']['tmp_name'];
        
        // Procesar el archivo según su tipo
        if ($extension === 'csv') {
            $productos = procesarCSV($archivoTemp, $pdo, $id_agricultor);
        } else {
            $productos = procesarExcel($archivoTemp, $pdo, $id_agricultor);
        }

        // Insertar productos en la base de datos
        $resultados = insertarProductos($productos, $pdo, $id_agricultor);
        
        $mensaje = "Carga masiva completada: " . 
                   $resultados['insertados'] . " productos insertados, " . 
                   $resultados['actualizados'] . " actualizados, " . 
                   $resultados['errores'] . " errores";
        $tipoMensaje = $resultados['errores'] > 0 ? 'warning' : 'success';

    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
        $tipoMensaje = 'danger';
    }
}

// Función para procesar archivos CSV
function procesarCSV($archivoTemp, $pdo, $id_agricultor) {
    $productos = [];
    $fila = 0;
    
    if (($handle = fopen($archivoTemp, "r")) !== FALSE) {
        // Saltar la primera fila (encabezados)
        $encabezados = fgetcsv($handle, 1000, ",");
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $fila++;
            $producto = validarFilaProducto($data, $fila, $pdo, $id_agricultor);
            if ($producto) {
                $productos[] = $producto;
            }
        }
        fclose($handle);
    }
    
    return $productos;
}

// Función para procesar archivos Excel (necesita la librería PhpSpreadsheet)
function procesarExcel($archivoTemp, $pdo, $id_agricultor) {
    // Verificar si PhpSpreadsheet está disponible
    if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        throw new Exception('La librería PhpSpreadsheet no está instalada. Instala con: composer require phpoffice/phpspreadsheet');
    }
    
    require_once '../vendor/autoload.php';
    
    $productos = [];
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivoTemp);
    $worksheet = $spreadsheet->getActiveSheet();
    $fila = 0;
    
    foreach ($worksheet->getRowIterator() as $row) {
        $fila++;
        // Saltar la primera fila (encabezados)
        if ($fila === 1) continue;
        
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(FALSE);
        
        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue();
        }
        
        $producto = validarFilaProducto($data, $fila, $pdo, $id_agricultor);
        if ($producto) {
            $productos[] = $producto;
        }
    }
    
    return $productos;
}

// Función para validar una fila de producto
function validarFilaProducto($data, $fila, $pdo, $id_agricultor) {
    // Validar que tenga al menos 5 columnas (nombre, descripción, categoría, precio, stock)
    if (count($data) < 5) {
        error_log("Fila $fila: No tiene suficientes columnas");
        return null;
    }
    
    $producto = [
        'nombre' => trim($data[0]),
        'descripcion' => trim($data[1]),
        'id_categoria' => obtenerIdCategoria(trim($data[2]), $pdo),
        'precio_unitario' => floatval(str_replace(['$', ','], '', $data[3])),
        'stock' => intval($data[4]),
        'id_unidad' => isset($data[5]) ? obtenerIdUnidad(trim($data[5]), $pdo) : 1, // Unidad por defecto
    ];
    
    // Validaciones básicas
    if (empty($producto['nombre'])) {
        error_log("Fila $fila: Nombre vacío");
        return null;
    }
    
    if ($producto['precio_unitario'] <= 0) {
        error_log("Fila $fila: Precio inválido");
        return null;
    }
    
    if ($producto['stock'] < 0) {
        error_log("Fila $fila: Stock inválido");
        return null;
    }
    
    if (!$producto['id_categoria']) {
        error_log("Fila $fila: Categoría no encontrada");
        return null;
    }
    
    return $producto;
}

// Función para obtener ID de categoría por nombre
function obtenerIdCategoria($nombreCategoria, $pdo) {
    $stmt = $pdo->prepare("SELECT id_categoria FROM categoria WHERE nombre = ?");
    $stmt->execute([$nombreCategoria]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result ? $result['id_categoria'] : null;
}

// Función para obtener ID de unidad por nombre
function obtenerIdUnidad($nombreUnidad, $pdo) {
    $stmt = $pdo->prepare("SELECT id_unidad FROM unidades WHERE nombre_unidad = ?");
    $stmt->execute([$nombreUnidad]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result ? $result['id_unidad'] : 1; // Default a 1 si no existe
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
            // Verificar si el producto ya existe (por nombre y agricultor)
            $stmt = $pdo->prepare("SELECT id_producto FROM productos WHERE nombre = ? AND id_agricultor = ?");
            $stmt->execute([$producto['nombre'], $id_agricultor]);
            $existente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existente) {
                // Actualizar producto existente
                $stmt = $pdo->prepare("
                    UPDATE productos SET 
                        descripcion = ?, id_categoria = ?, precio_unitario = ?, 
                        stock = ?, id_unidad = ?, fecha_publicacion = NOW()
                    WHERE id_producto = ?
                ");
                $stmt->execute([
                    $producto['descripcion'],
                    $producto['id_categoria'],
                    $producto['precio_unitario'],
                    $producto['stock'],
                    $producto['id_unidad'],
                    $existente['id_producto']
                ]);
                $resultados['actualizados']++;
            } else {
                // Insertar nuevo producto
                $stmt = $pdo->prepare("
                    INSERT INTO productos (
                        id_agricultor, id_categoria, descripcion, nombre, 
                        stock, precio_unitario, id_unidad, fecha_publicacion
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([
                    $id_agricultor,
                    $producto['id_categoria'],
                    $producto['descripcion'],
                    $producto['nombre'],
                    $producto['stock'],
                    $producto['precio_unitario'],
                    $producto['id_unidad']
                ]);
                $resultados['insertados']++;
            }
        } catch (Exception $e) {
            error_log("Error insertando producto: " . $e->getMessage());
            $resultados['errores']++;
        }
    }
    
    return $resultados;
}
?>