<?php
// Configurar codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

include 'db.php';
include 'session.php';
checkLogin();

$id_usuario = $_SESSION['id_usuario'];

if (isAdmin()) {
    $sql = "SELECT r.id_reservacion, u.nombre AS usuario, d.ciudad, d.hotel, r.fecha_reserva, r.numero_personas, r.costo_total
            FROM reservaciones r
            JOIN usuarios u ON r.id_usuario = u.id_usuario
            JOIN destinos d ON r.id_destino = d.id_destino";
    $reservaciones = $conn->query($sql);
} else {
    $stmt = $conn->prepare("SELECT r.id_reservacion, u.nombre AS usuario, d.ciudad, d.hotel, r.fecha_reserva, r.numero_personas, r.costo_total
                            FROM reservaciones r
                            JOIN usuarios u ON r.id_usuario = u.id_usuario
                            JOIN destinos d ON r.id_destino = d.id_destino
                            WHERE r.id_usuario = ?");

    if ($stmt) {
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $reservaciones = $stmt->get_result();
        $stmt->close();
    } else {
        // Manejar el error de la consulta preparada
        $reservaciones = null;
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
}
?>

<?php include 'views/header.php'; ?>
<main>
    <h2>Lista de Reservaciones</h2>
    
    <?php
    // Mostrar mensajes de éxito o error
    if (isset($_GET['mensaje'])) {
        if ($_GET['mensaje'] == 'eliminado') {
            echo '<div class="alert alert-success">Reservación eliminada exitosamente.</div>';
        }
    }
    
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        $mensaje_error = '';
        switch($error) {
            case 'no_id':
                $mensaje_error = 'ID de reservación no especificado.';
                break;
            case 'id_invalido':
                $mensaje_error = 'ID de reservación inválido.';
                break;
            case 'no_encontrado':
                $mensaje_error = 'Reservación no encontrada o no tienes permisos para eliminarla.';
                break;
            case 'eliminar_error':
                $mensaje_error = 'Error al eliminar la reservación.';
                break;
            default:
                $mensaje_error = 'Error desconocido.';
        }
        echo '<div class="alert alert-error">' . htmlspecialchars($mensaje_error) . '</div>';
    }
    ?>
    
    <?php if ($reservaciones && $reservaciones->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Destino</th>
                    <th>Hotel</th>
                    <th>Fecha</th>
                    <th>Personas</th>
                    <th>Costo Total</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $reservaciones->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                        <td><?php echo htmlspecialchars($row['ciudad']); ?></td>
                        <td><?php echo htmlspecialchars($row['hotel']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_reserva']); ?></td>
                        <td><?php echo htmlspecialchars($row['numero_personas']); ?></td>
                        <td>$<?php echo htmlspecialchars($row['costo_total']); ?></td>
                        <td><a class="btn-modificar" href="modificar.php?id=<?php echo htmlspecialchars($row['id_reservacion']); ?>">Modificar</a></td>
                        <td><a class="btn-eliminar" href="eliminar.php?id=<?php echo htmlspecialchars($row['id_reservacion']); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta reservación?');">Eliminar</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Botón de exportación CSV -->
        <form method="POST" action="exportar_csv.php" style="margin-top:15px;">
            <input type="submit" value="Exportar CSV">
        </form>

    <?php else: ?>
        <p>No hay reservaciones para mostrar.</p>
    <?php endif; ?>
</main>
<?php include 'views/footer.php'; ?>
