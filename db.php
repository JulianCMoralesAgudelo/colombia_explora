<?php
// ====================
// Configuración de conexión a MySQL
// ====================

// Para Docker/entorno local
$host = "db";       // o "127.0.0.1"
$user = "root";
$pass = "rootDB*";
$db   = "viajes";

/*
// Para InfinityFree (o cualquier otro hosting)
$host = "sqlXXX.epizy.com";
$user = "tu_usuario";
$pass = "tu_password";
$db   = "nombre_bd";
*/

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    // Para producción, se recomienda registrar el error en un log y mostrar un mensaje genérico.
    // die("Conexión fallida: " . $conn->connect_error);
    http_response_code(500); // Internal Server Error
    echo "<h1>Error en el servidor</h1>";
    exit();
}

// Establecer codificación UTF-8 
$conn->set_charset("utf8mb4");

// Configuración adicional para UTF-8
$conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->query("SET CHARACTER SET utf8mb4");
