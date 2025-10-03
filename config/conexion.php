<?php
// Obtener la URL de conexión desde la variable de entorno
$mysql_url = getenv('MYSQL_URL');

// Verificar si la URL de conexión fue obtenida correctamente
if ($mysql_url) {
    try {
        $pdo = new PDO($mysql_url);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // En producción no mostrar mensajes, solo conectar
    } catch (PDOException $e) {
        // Puedes loguear el error si lo necesitas
        die("Error al conectar: " . $e->getMessage());
    }
} else {
    die("No se pudo obtener la URL de conexión.");
}
?>