<?php
// conexion.php

// Datos de conexión
$host = 'localhost';
$usuario_db = 'root'; 
$contrasena_db = ''; 
$nombre_db = 'agro_app.sql'; // Asegúrate de que esta línea esté así

// Inicializar la variable de conexión.
$pdo = null;

try {
    // La cadena DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$nombre_db;charset=utf8mb4";
    
    // Crear el objeto PDO y la conexión a la base de datos
    $pdo = new PDO($dsn, $usuario_db, $contrasena_db);
    
    // Configurar PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurar PDO para que los resultados se devuelvan como un array asociativo
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si la conexión falla, detiene el script con un mensaje claro.
    die("<p style='color: red;'>Error CRÍTICO: Fallo en la conexión a la base de datos: " . htmlspecialchars($e->getMessage()) . "</p>");
}
?>