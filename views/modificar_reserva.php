<?php include 'views/header.php'; ?>

<main>
    <h2>Modificar Reservación</h2>

    <?php if (isset($_GET['error'])): ?>
        <p>Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form method="POST" id="form-modificar">
        <label>Fecha de Reserva:</label><br>
        <input type="date" name="fecha_reserva" value="<?php echo htmlspecialchars($reservacion['fecha_reserva'] ?? ''); ?>" required><br><br>

        <label>Número de Personas:</label><br>
        <input type="number" name="numero_personas" id="numero_personas" min="1" value="<?php echo htmlspecialchars($reservacion['numero_personas'] ?? 1); ?>" required><br><br>

        <label>Costo Total (calculado):</label><br>
        <input type="text" name="costo_total" id="costo_total" value="<?php echo number_format((float)($reservacion['costo_total'] ?? 0), 2, '.', ''); ?>" readonly><br><br>

        <button type="submit">Guardar Cambios</button>
        <a href="listar.php">Cancelar</a>
    </form>
</main>

<script>
(function () {
    const precio = <?php echo json_encode((float)($reservacion['costo_por_persona'] ?? 0)); ?>;
    const numInput = document.getElementById('numero_personas');
    const costoInput = document.getElementById('costo_total');

    function actualizar() {
        const num = parseInt(numInput.value, 10) || 0;
        costoInput.value = (num * precio).toFixed(2);
    }

    numInput.addEventListener('input', actualizar);
    // Inicializar el valor al cargar
    actualizar();
})();
</script>

<?php include 'views/footer.php'; ?>
