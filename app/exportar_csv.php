<?php
include 'db.php';
include 'session.php';
checkLogin();

$id_usuario = $_SESSION['id_usuario'];

// Consultar según sea admin o usuario normal
if (isAdmin()) {
    $sql = "SELECT r.id_reservacion, u.nombre AS usuario, d.ciudad, d.hotel, r.fecha_reserva, r.numero_personas, r.costo_total
            FROM reservaciones r
            JOIN usuarios u ON r.id_usuario = u.id_usuario
            JOIN destinos d ON r.id_destino = d.id_destino";
    $result = $conn->query($sql);
} else {
    $stmt = $conn->prepare("SELECT r.id_reservacion, u.nombre AS usuario, d.ciudad, d.hotel, r.fecha_reserva, r.numero_personas, r.costo_total
                            FROM reservaciones r
                            JOIN usuarios u ON r.id_usuario = u.id_usuario
                            JOIN destinos d ON r.id_destino = d.id_destino
                            WHERE r.id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}

// Configurar headers para descarga CSV
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=reservaciones_export.csv");

// Abrir flujo de salida
$output = fopen("php://output", "w");

// Escribir encabezados de columnas
fputcsv($output, ['ID', 'Usuario', 'Ciudad', 'Hotel', 'Fecha Reserva', 'Personas', 'Costo Total']);

// Escribir filas
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>