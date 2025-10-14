<?php
// Configurar codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

// 1. INCLUSIONES
include 'db.php';
include_once include 'models/Reservacion.php';
// CORRECCIÓN: Usar la ruta correcta para el archivo compartido
include __DIR__ . '/../shared/session.php'; 

checkLogin();

// Instanciar Modelo
$reservacionModel = new Reservacion($conn);
$id_usuario = $_SESSION['id_usuario'];

// 2. LÓGICA MVC: Consultar según sea admin o usuario normal
if (isAdmin()) {
    // Admin: Usar el método del modelo para obtener TODAS las reservaciones
    $result = $reservacionModel->getAllReservaciones();
} else {
    // Usuario: Usar el método del modelo para obtener solo SUS reservaciones
    $result = $reservacionModel->getReservacionesByUsuario($id_usuario);
}

// 3. CONFIGURACIÓN DE DESCARGA CSV
// Configurar headers para descarga CSV
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=reservaciones_export.csv");

// Abrir flujo de salida
$output = fopen("php://output", "w");

// Escribir encabezados de columnas (Asegúrate de que el orden coincida con la consulta SQL)
fputcsv($output, ['ID', 'Usuario', 'Ciudad', 'Hotel', 'Fecha Reserva', 'Personas', 'Costo Total']);

// 4. GENERAR FILAS
if ($result) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
exit;
?>