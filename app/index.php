<?php
// Configurar codificación UTF-8
header('Content-Type: text/html; charset=utf-8');

include 'db.php';
include 'session.php';
// session_start(); <-- ¡ELIMINADO!

$result = $conn->query("SELECT * FROM destinos");
?>

<?php include 'views/header.php'; ?>

<section class="banner">
    <img src="assets/img/banner.jpg" alt="Banner Viajes Colombia" class="banner-img">
    <h2>Explora Colombia con nosotros</h2>
    <?php if(isset($_SESSION['id_usuario'])): ?>
        <a href="reserva.php">Reserva tu tour</a>
    <?php else: ?>
        <a href="login.php">Inicia sesión para reservar</a>
    <?php endif; ?>
</section>

<section class="destinos">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="tarjeta">
            <img src="assets/img/<?php echo htmlspecialchars(strtolower($row['ciudad'])); ?>.jpg" 
                 alt="<?php echo htmlspecialchars($row['ciudad']); ?>" class="destino-img">
            <h4><?php echo htmlspecialchars($row['ciudad']); ?></h4>
            <p>Hotel: <?php echo htmlspecialchars($row['hotel']); ?></p>
            <a href="reserva.php">Reservar</a>
        </div>
    <?php endwhile; ?>
</section>

<?php include 'views/footer.php'; ?>