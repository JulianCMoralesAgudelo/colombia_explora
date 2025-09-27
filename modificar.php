<?php
// modificar.php
include 'db.php';
include 'session.php';

checkLogin();

// Validar ID en GET
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: listar.php?mensaje=id_invalido");
    exit();
}
$id = (int) $_GET['id'];

// Si envían el formulario (POST) procesamos la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_reserva   = trim($_POST['fecha_reserva'] ?? '');
    $numero_personas = trim($_POST['numero_personas'] ?? '');

    // Validaciones básicas
    if ($fecha_reserva === '' || !ctype_digit($numero_personas) || (int)$numero_personas <= 0) {
        header("Location: modificar.php?id={$id}&error=datos_invalidos");
        exit();
    }
    $numero_personas = (int) $numero_personas;

    // Obtener costo por persona desde la tabla destinos (relacionada a la reservación)
    $stmt = $conn->prepare(
        "SELECT d.costo 
         FROM reservaciones r
         JOIN destinos d ON r.id_destino = d.id_destino
         WHERE r.id_reservacion = ?"
    );
    if (!$stmt) {
        header("Location: listar.php?mensaje=error_db");
        exit();
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        $stmt->close();
        header("Location: listar.php?mensaje=reservacion_no_encontrada");
        exit();
    }
    $row = $res->fetch_assoc();
    $costo_por_persona = (float) $row['costo'];
    $stmt->close();

    // Calcular costo_total en servidor (no confiar en lo que envía el cliente)
    $costo_total = $numero_personas * $costo_por_persona;

    // Actualizar la reservación
    $stmt2 = $conn->prepare(
        "UPDATE reservaciones 
         SET fecha_reserva = ?, numero_personas = ?, costo_total = ?
         WHERE id_reservacion = ?"
    );
    if (!$stmt2) {
        header("Location: modificar.php?id={$id}&error=prepare_fail");
        exit();
    }
    $stmt2->bind_param("sidi", $fecha_reserva, $numero_personas, $costo_total, $id);

    if ($stmt2->execute()) {
        $stmt2->close();
        header("Location: listar.php?mensaje=modificado");
        exit();
    } else {
        $stmt2->close();
        header("Location: modificar.php?id={$id}&error=update_fail");
        exit();
    }
}

// Si NO es POST: obtener datos actuales para mostrar el formulario
$stmt = $conn->prepare(
    "SELECT r.fecha_reserva, r.numero_personas, r.costo_total, d.costo AS costo_por_persona
     FROM reservaciones r
     JOIN destinos d ON r.id_destino = d.id_destino
     WHERE r.id_reservacion = ?"
);
if (!$stmt) {
    header("Location: listar.php?mensaje=error_db");
    exit();
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: listar.php?mensaje=reservacion_no_encontrada");
    exit();
}
$reservacion = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Incluir la vista (archivo que contiene el formulario)
include 'views/modificar_reserva.php';
