<?php
// Configurar codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

// 1. INCLUSIONES
include 'db.php';
// Incluir Modelos
include_once include 'models/Reservacion.php'; 
include 'models/Destino.php';
// CORRECCIÓN: Usar la ruta correcta para el archivo compartido
include __DIR__ . '/../shared/session.php'; 

checkLogin();

// Instanciar Modelos
$reservacionModel = new Reservacion($conn);
$destinoModel = new Destino($conn);

// Redireccion por defecto (corregida a views/listar_reservaciones.php)
$listar_url = "views/listar_reservaciones.php";

// Validar ID en GET
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: {$listar_url}?mensaje=id_invalido");
    exit();
}
$id_reserva = (int) $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];
$is_admin = isAdmin();

// --- LÓGICA POST (ACTUALIZACIÓN) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_reserva   = trim($_POST['fecha_reserva'] ?? '');
    $numero_personas = trim($_POST['numero_personas'] ?? '');

    // Validaciones básicas
    if ($fecha_reserva === '' || !ctype_digit($numero_personas) || (int)$numero_personas <= 0) {
        header("Location: modificar.php?id={$id_reserva}&error=datos_invalidos");
        exit();
    }
    $numero_personas = (int) $numero_personas;

    // 1. MVC: Obtener datos actuales de la reserva para verificar el propietario y el id_destino
    $res = $reservacionModel->getReservacionFullDataById($id_reserva); // Asumo este método existe o lo crearemos
    $reserva_actual = $res->fetch_assoc();

    if (!$reserva_actual) {
        header("Location: {$listar_url}?mensaje=reservacion_no_encontrada");
        exit();
    }

    // 2. SEGURIDAD: Verificar que el usuario tenga permiso para modificar
    if (!$is_admin && $reserva_actual['id_usuario'] !== $id_usuario) {
        // Si no es admin y no es su reserva
        header("Location: {$listar_url}?error=permiso_denegado");
        exit();
    }

    // 3. Lógica de Negocio/MVC: Recalcular costo total
    $id_destino = $reserva_actual['id_destino'];
    $destino_res = $destinoModel->getDestinoById($id_destino);
    $destino_data = $destino_res->fetch_assoc();
    
    $costo_por_persona = (float) $destino_data['costo'];
    $costo_total = $numero_personas * $costo_por_persona;
    
    // 4. MVC: Actualizar la reservación
    $success = $reservacionModel->updateReservacion(
        $id_reserva, 
        $fecha_reserva, 
        $numero_personas, 
        $costo_total
    );

    if ($success) {
        header("Location: {$listar_url}?mensaje=modificado");
        exit();
    } else {
        header("Location: modificar.php?id={$id_reserva}&error=update_fail");
        exit();
    }
}

// --- LÓGICA GET (MOSTRAR FORMULARIO) ---

// 1. MVC: Obtener datos actuales de la reserva para mostrar en el formulario
$res = $reservacionModel->getReservacionFullDataById($id_reserva); // Asumo este método existe o lo crearemos
$reservacion = $res->fetch_assoc();

if (!$reservacion) {
    header("Location: {$listar_url}?mensaje=reservacion_no_encontrada");
    exit();
}

// 2. SEGURIDAD: Verificar que el usuario tenga permiso para ver/modificar
if (!$is_admin && $reservacion['id_usuario'] !== $id_usuario) {
    // Si no es admin y no es su reserva
    header("Location: {$listar_url}?error=permiso_denegado");
    exit();
}

// Nota: Para la vista modificar_reserva.php, la variable $reservacion debe contener todos los campos necesarios.

$conn->close();

// Incluir la vista (archivo que contiene el formulario)
include 'views/modificar_reserva.php';