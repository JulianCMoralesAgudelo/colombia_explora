<?php
// ==========================
// Conexión a MySQL en Docker
// ==========================
// Usar las variables de entorno definidas en docker-compose.yml
$host = $_ENV['DB_HOST'] ?? 'db';           // ✅ 'db' es el nombre del servicio
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? 'rootDB*';      // ✅ Tu contraseña real
$db   = $_ENV['DB_NAME'] ?? 'viajes';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Error de conexión a MySQL: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>