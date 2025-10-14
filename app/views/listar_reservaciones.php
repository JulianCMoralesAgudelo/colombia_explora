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

// Obtener reservaciones
$reservaciones = $reservacionModel->getReservacionesByUsuario($_SESSION['id_usuario']);
?>

<?php include __DIR__ . '/header.php'; ?>

<div class="main-container">
    <section class="modern-banner">
        <h2>🎯 Mis Reservaciones</h2>
        <p>Gestiona todas tus reservas de viaje en un solo lugar</p>
    </section>

    <div class="modern-table-container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                ✅ Reservación <?php echo $_GET['success'] === 'updated' ? 'actualizada' : 'creada'; ?> exitosamente!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">
                ✅ Reservación eliminada exitosamente!
            </div>
        <?php endif; ?>

        <table class="modern-table">
            <thead>
                <tr>
                    <th>Destino</th>
                    <th>Hotel</th>
                    <th>Fecha</th>
                    <th>Personas</th>
                    <th>Costo Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($reserva = $reservaciones->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($reserva['ciudad']); ?></strong></td>
                    <td><?php echo htmlspecialchars($reserva['hotel']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['fecha_reserva']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['numero_personas']); ?></td>
                    <td><strong>$<?php echo number_format($reserva['costo_total'], 0, ',', '.'); ?></strong></td>
                    <td>
                        <a href="/views/modificar_reserva.php?id=<?php echo $reserva['id_reservacion']; ?>" 
                           class="btn-modificar">✏️ Modificar</a>
                        <a href="/eliminar.php?id=<?php echo $reserva['id_reservacion']; ?>" 
                           class="btn-eliminar" 
                           onclick="return confirm('¿Estás seguro de eliminar esta reservación?')">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>