<?php
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
                        <td><a class="btn-eliminar" href="eliminar.php?id=<?php echo htmlspecialchars($row['id_reservacion']); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta reservación?');">Eliminar</a></td></tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay reservaciones para mostrar.</p>
    <?php endif; ?>
</main>
<?php include 'views/footer.php'; ?>