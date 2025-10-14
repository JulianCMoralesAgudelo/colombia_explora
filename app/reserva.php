<?php
header('Content-Type: text/html; charset=utf-8');
include 'db.php';
include 'models/Destino.php';
include __DIR__ . '/../shared/session.php';

checkLogin();

$destinoModel = new Destino($conn);
$destinos = $destinoModel->getDestinos();
?>

<?php include 'views/header.php'; ?>

<div class="main-container">
    <section class="modern-banner">
        <h2>🎫 Haz tu Reserva</h2>
        <p>Planifica tu viaje perfecto a Colombia</p>
    </section>

    <div class="modern-table-container">
        <form method="POST" action="/guardar.php" class="modern-form">
            <div class="form-group">
                <label for="destino">🏝️ Destino:</label>
                <select id="destino" name="destino" required>
                    <option value="">Selecciona un destino</option>
                    <?php while($destino = $destinos->fetch_assoc()): ?>
                        <option value="<?php echo $destino['id_destino']; ?>" data-precio="<?php echo $destino['costo']; ?>">
                            <?php echo htmlspecialchars($destino['ciudad'] . ' - ' . $destino['hotel'] . ' ($' . number_format($destino['costo'], 0, ',', '.') . ')'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="fecha">📅 Fecha del Viaje:</label>
                <input type="date" id="fecha" name="fecha" required 
                       min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="personas">👥 Número de Personas:</label>
                <input type="number" id="personas" name="personas" 
                       min="1" max="10" value="1" required>
            </div>

            <div class="form-group">
                <label for="costo_total">💰 Costo Total:</label>
                <input type="text" id="costo_total" value="$0" readonly 
                       class="readonly-input total-cost">
                <small>El costo se calcula automáticamente</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-reservar">✅ Confirmar Reserva</button>
                <a href="/index.php" class="btn-cancelar">↩️ Volver al Inicio</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const destinoSelect = document.getElementById('destino');
    const personasInput = document.getElementById('personas');
    const costoTotalInput = document.getElementById('costo_total');

    function calcularCosto() {
        const selectedOption = destinoSelect.options[destinoSelect.selectedIndex];
        const precio = selectedOption ? parseFloat(selectedOption.getAttribute('data-precio')) : 0;
        const personas = parseInt(personasInput.value) || 0;
        const total = precio * personas;
        
        costoTotalInput.value = total > 0 ? `$${total.toLocaleString()}` : '$0';
    }

    destinoSelect.addEventListener('change', calcularCosto);
    personasInput.addEventListener('input', calcularCosto);
    
    // Calcular inicialmente
    calcularCosto();
});
</script>

<?php include 'views/footer.php'; ?>