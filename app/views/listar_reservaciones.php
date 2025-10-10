<?php include 'views/header.php'; ?>

<main>
    <h2>Lista de Reservaciones</h2>
    <table>
        <tr>
            <th>Usuario</th>
            <th>Destino</th>
            <th>Hotel</th>
            <th>Fecha</th>
            <th>Personas</th>
            <th>Costo Total</th>
        </tr>
        <?php while($row = $reservaciones->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['usuario']; ?></td>
            <td><?php echo $row['ciudad']; ?></td>
            <td><?php echo $row['hotel']; ?></td>
            <td><?php echo $row['fecha_reserva']; ?></td>
            <td><?php echo $row['numero_personas']; ?></td>
            <td>$<?php echo $row['costo_total']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>

<?php include 'views/footer.php'; ?>
