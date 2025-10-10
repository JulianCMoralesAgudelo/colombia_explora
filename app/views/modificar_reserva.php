<?php 
// Configurar codificación UTF-8
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}
include 'views/header.php'; ?>

<main>
    <h2>Modificar Reservación</h2>

    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;">Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form method="POST" id="form-modificar">
        <label>Fecha de Reserva:</label>
        <input type="date" name="fecha_reserva" value="<?php echo htmlspecialchars($reservacion['fecha_reserva'] ?? ''); ?>" required>

        <label>Número de Personas:</label>
        <input type="number" name="numero_personas" id="numero_personas" min="1" value="<?php echo htmlspecialchars($reservacion['numero_personas'] ?? 1); ?>" required>

        <label>Costo Total (calculado automáticamente):</label>
        <input type="text" name="costo_total" id="costo_total" value="<?php echo number_format((float)($reservacion['costo_total'] ?? 0), 2, '.', ''); ?>" readonly style="background-color: #f5f5f5;">

        <input type="submit" value="Guardar Cambios">
        <br><br>
        <a href="listar.php" style="display: inline-block; padding: 10px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 8px; text-align: center;">Cancelar</a>
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
