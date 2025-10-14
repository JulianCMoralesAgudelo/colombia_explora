<?php
// Configurar codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

// CORRECCIÓN 1: Inclusión de archivos
// Incluir la conexión a DB
include 'db.php';
// Incluir Modelos necesarios
include 'models/Destino.php';
include_once include 'models/Reservacion.php';
// Incluir el manejador de sesión compartido con la ruta correcta
include __DIR__ . '/../shared/session.php';

// SEGURIDAD: Asegura que el usuario esté autenticado. ¡Correcto!
checkLogin();

// Instanciar Modelos
$destinoModel = new Destino($conn);
$reservacionModel = new Reservacion($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. VALIDACIÓN Y SANEAMIENTO DE ENTRADAS
    $usuario_id = $_SESSION['id_usuario'];
    $destino_id = filter_var($_POST['destino'], FILTER_VALIDATE_INT);
    $fecha_reserva = $_POST['fecha'];
    $num_personas = filter_var($_POST['personas'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

    if ($destino_id === false || $num_personas === false || empty($fecha_reserva)) {
        // Manejar el error de entrada
        header("Location: reserva.php?error=invalid_input");
        exit();
    }

    // 2. LÓGICA DE NEGOCIO (MVC): Obtener costo del destino usando el Modelo
    $result = $destinoModel->getDestinoById($destino_id);
    $destino_data = $result->fetch_assoc();

    if (!$destino_data) {
        // El destino no existe
        header("Location: reserva.php?error=destino_not_found");
        exit();
    }

    $costo_base = $destino_data['costo'];
    $costo_total = $costo_base * $num_personas;

    // 3. INSERCIÓN DE DATOS (MVC): Insertar la reservación usando el Modelo
    $success = $reservacionModel->createReservacion(
        $usuario_id,
        $destino_id,
        $fecha_reserva,
        $num_personas, // Campo num_personas añadido por el schema.sql
        $costo_total
    );

    if ($success) {
        // ✅ CORRECCIÓN: Redireccionar a la ruta correcta
        header("Location: views/listar_reservaciones.php?success=true");
        exit();
    } else {
        // Manejar error de inserción
        header("Location: reserva.php?error=db_insert_failed");
        exit();
    }
}