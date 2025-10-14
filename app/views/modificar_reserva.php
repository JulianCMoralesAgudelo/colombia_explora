<?php
// Configurar codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

// Includes corregidos
include_once __DIR__ . '/../db.php';
include_once __DIR__ . '/../models/Reservacion.php';
include __DIR__ . '/../../shared/session.php';

// Verificar autenticación
checkLogin();

// Instanciar modelo
$reservacionModel = new Reservacion($conn);

// Obtener ID de la reservación
$id_reservacion = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id_reservacion) {
    header("Location: /views/listar_reservaciones.php?error=id_invalido");
    exit();
}

// Obtener datos actuales de la reservación
$result = $reservacionModel->getReservacionFullDataById($id_reservacion);
$reservacion = $result->fetch_assoc();

// Verificar que la reservación existe y pertenece al usuario
if (!$reservacion || $reservacion['id_usuario'] != $_SESSION['id_usuario']) {
    header("Location: /views/listar_reservaciones.php?error=reserva_no_encontrada");
    exit();
}

// Obtener costo por persona (costo_total / numero_personas)
$costo_por_persona = $reservacion['costo_total'] / $reservacion['numero_personas'];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_reserva = $_POST['fecha_reserva'];
    $numero_personas = (int)$_POST['numero_personas'];
    $costo_total = $numero_personas * $costo_por_persona;

    // Validaciones
    if ($numero_personas < 1) {
        header("Location: /views/modificar_reserva.php?id=$id_reservacion&error=personas_invalidas");
        exit();
    }

    // Actualizar reservación
    $success = $reservacionModel->updateReservacion(
        $id_reservacion,
        $fecha_reserva,
        $numero_personas,
        $costo_total
    );

    if ($success) {
        header("Location: /views/listar_reservaciones.php?success=updated");
        exit();
    } else {
        header("Location: /views/modificar_reserva.php?id=$id_reservacion&error=actualizacion_fallida");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Reservación - Viajes Colombia</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>

<div class="main-container">
    <section class="modern-banner">
        <h2>✏️ Modificar Reservación</h2>
        <p>Actualiza los detalles de tu viaje</p>
    </section>

    <div class="modern-table-container">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                ❌ Error: <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="modern-form">
            <div class="form-group">
                <label for="fecha_reserva">Fecha de Reserva:</label>
                <input type="date" id="fecha_reserva" name="fecha_reserva" 
                       value="<?php echo htmlspecialchars($reservacion['fecha_reserva'] ?? ''); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="numero_personas">Número de Personas:</label>
                <input type="number" id="numero_personas" name="numero_personas" 
                       min="1" max="10" 
                       value="<?php echo htmlspecialchars($reservacion['numero_personas'] ?? 1); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="costo_total">Costo Total (calculado automáticamente):</label>
                <input type="text" id="costo_total" name="costo_total" 
                       value="<?php echo number_format((float)($reservacion['costo_total'] ?? 0), 2, '.', ''); ?>" 
                       readonly class="readonly-input">
                <small>Costo por persona: $<?php echo number_format($costo_por_persona, 2, '.', ','); ?></small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-modificar">💾 Guardar Cambios</button>
                <a href="/views/listar_reservaciones.php" class="btn-cancelar">❌ Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const precio = <?php echo json_encode((float)$costo_por_persona); ?>;
    const numInput = document.getElementById('numero_personas');
    const costoInput = document.getElementById('costo_total');

    function actualizar() {
        const num = parseInt(numInput.value, 10) || 0;
        const total = (num * precio).toFixed(2);
        costoInput.value = total.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    numInput.addEventListener('input', actualizar);
    // Inicializar el valor al cargar
    actualizar();
})();
</script>

<?php include __DIR__ . '/footer.php'; ?>
</body>
</html>