<?php
// Configuración de conexión a MySQL

// ====================
// Para prueba local con Docker
// ====================
$host = "127.0.0.1";   // o nombre del contenedor
$user = "root";        // usuario de MySQL
$pass = "rootDB*";     // contraseña de MySQL
$db   = "viajes";      // nombre de la base de datos

/*
// ====================
// Para InfinityFree (después de subir el proyecto)
// ====================
// $host = "sqlXXX.epizy.com";  // host proporcionado por InfinityFree
// $user = "tu_usuario";        // usuario de la BD en InfinityFree
// $pass = "tu_password";       // contraseña
// $db   = "nombre_bd";         // nombre de la BD en InfinityFree
*/

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer codificación UTF-8
$conn->set_charset("utf8mb4");
?>
