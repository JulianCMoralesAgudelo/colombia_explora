<?php
// Configurar codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

// 1. INCLUSIONES
include 'db.php';
include_once include 'models/Reservacion.php';
// CORRECCIÓN: Usar la ruta correcta para el archivo compartido
include __DIR__ . '/../shared/session.php'; 

// 2. SEGURIDAD BÁSICA Y VERIFICACIÓN DE SESIÓN
checkLogin(); 

if (!isset($_GET['id'])) {
    header("Location: views/listar_reservaciones.php?error=no_id");
    exit();
}

$id_reserva = $_GET['id'];

// Validar que el ID sea numérico
if (!ctype_digit($id_reserva)) {
    header("Location: views/listar_reservaciones.php?error=id_invalido");
    exit();
}

$id_reserva = (int) $id_reserva;
$id_usuario = $_SESSION['id_usuario'];

// Instanciar Modelo
$reservacionModel = new Reservacion($conn);

// 3. LÓGICA MVC Y CONTROL DE ACCESO
// isAdmin() se define en shared/session.php, lo cual es correcto.

if (isAdmin()) {
    // Admin: Puede eliminar cualquier reserva. Pasamos el ID de usuario como 0 o null para indicar bypass de verificación.
    $deleted_rows = $reservacionModel->deleteReservacion($id_reserva, 0); 
} else {
    // Usuario: Solo puede eliminar su propia reserva.
    $deleted_rows = $reservacionModel->deleteReservacion($id_reserva, $id_usuario);
}

// 4. MANEJO DE RESULTADOS
if ($deleted_rows === false) {
    // Error de ejecución de la consulta
    header("Location: views/listar_reservaciones.php?error=eliminar_error");
} elseif ($deleted_rows > 0) {
    // Eliminación exitosa
    header("Location: views/listar_reservaciones.php?mensaje=eliminado");
} else {
    // No se encontró la reserva O el usuario intentó borrar la reserva de otro.
    header("Location: views/listar_reservaciones.php?error=no_encontrado");
}

$conn->close();
exit();