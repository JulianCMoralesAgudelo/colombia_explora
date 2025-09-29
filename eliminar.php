<?php
// Configurar codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

include 'db.php';
include 'session.php';

// Verificar que el usuario esté logueado
checkLogin();

if (!isset($_GET['id'])) {
    header("Location: listar.php?error=no_id");
    exit();
}

$id = $_GET['id'];

// Validar que el ID sea numérico
if (!ctype_digit($id)) {
    header("Location: listar.php?error=id_invalido");
    exit();
}

$id = (int) $id;
$id_usuario = $_SESSION['id_usuario'];

// Verificar permisos: Admin puede eliminar cualquier reservación, usuario solo las suyas
if (isAdmin()) {
    // Admin puede eliminar cualquier reservación
    $stmt = $conn->prepare("DELETE FROM reservaciones WHERE id_reservacion = ?");
    $stmt->bind_param("i", $id);
} else {
    // Usuario solo puede eliminar sus propias reservaciones
    $stmt = $conn->prepare("DELETE FROM reservaciones WHERE id_reservacion = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id, $id_usuario);
}

if (!$stmt) {
    header("Location: listar.php?error=prepare_error");
    exit();
}

// Ejecutar la eliminación
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header("Location: listar.php?mensaje=eliminado");
    } else {
        header("Location: listar.php?error=no_encontrado");
    }
} else {
    header("Location: listar.php?error=eliminar_error");
}

$stmt->close();
$conn->close();
exit();
?>