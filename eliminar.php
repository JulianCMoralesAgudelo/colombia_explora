<?php
include 'db.php';
include 'session.php'; // Incluimos session.php para tener acceso a las funciones de sesión


if (!isset($_GET['id'])) {
    die("ID de reservación no especificado.");
}

$id = $_GET['id'];

if (!ctype_digit($id)) {
    die("ID inválido.");
}

$id = (int) $id;

// Preparar sentencia SQL OJO MODIFICAR LA QUERY SI SE LLAMA DIFETENTE LA BASE DE DATOS
$stmt = $conn->prepare("DELETE FROM colombia_explora.reservaciones WHERE id_reservacion = ?");
if (!$stmt) {
    die("Error en prepare: " . $conn->error);
}

$stmt->bind_param("i", $id);

// Ejecutar
if ($stmt->execute()) {
    header("Location: listar.php?mensaje=eliminado");
    exit();
} else {
    echo "Error al eliminar la reservación: " . $stmt->error;
}

$stmt->close();
$conn->close();