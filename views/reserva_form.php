<?php include 'views/header.php'; ?>

<main>
    <form method="POST" action="guardar.php">
        <h2>Formulario de Reservación</h2>
        <label>Destino:</label>
        <select name="destino" required>
            <?php while($row = $destinos->fetch_assoc()): ?>
                <option value="<?php echo $row['id_destino']; ?>">
                    <?php echo $row['ciudad'].' - '.$row['hotel'].' ($'.$row['costo'].')'; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Fecha del viaje:</label>
        <input type="date" name="fecha" required>

        <label>Número de personas:</label>
        <input type="number" name="personas" min="1" required>

        <input type="submit" value="Reservar">
    </form>
</main>

<?php include 'views/footer.php'; ?>
