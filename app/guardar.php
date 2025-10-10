<?php
include 'db.php';
include 'session.php';
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y sanear las entradas
    $usuario = $_SESSION['id_usuario'];
    $destino = filter_var($_POST['destino'], FILTER_VALIDATE_INT);
    $fecha = $_POST['fecha'];
    $personas = filter_var($_POST['personas'], FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

    if ($destino === false || $personas === false || $fecha === null) {
        // Manejar el error, por ejemplo, redirigir con un mensaje de error
        header("Location: reserva.php?error=invalid_input");
        exit();
    }

    // Obtener costo del destino
    $stmt = $conn->prepare("SELECT costo FROM destinos WHERE id_destino = ?");
    $stmt->bind_param("i", $destino);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // El destino no existe
        header("Location: reserva.php?error=destino_not_found");
        exit();
    }

    $row = $result->fetch_assoc();
    $costo = $row['costo'];
    $stmt->close();

    $costo_total = $costo * $personas;

    // Insertar la reservación
    $stmt = $conn->prepare("INSERT INTO reservaciones (id_usuario, id_destino, fecha_reserva, numero_personas, costo_total) VALUES (?,?,?,?,?)");
    $stmt->bind_param("iisid", $usuario, $destino, $fecha, $personas, $costo_total);
    $stmt->execute();
    $stmt->close();

    header("Location: listar.php");
    exit();
}
