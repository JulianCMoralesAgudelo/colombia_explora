<?php include 'views/header.php'; ?>

<section class="banner">
    <h2>Explora Colombia con nosotros</h2>
    <?php if(isset($_SESSION['id_usuario'])): ?>
        <a href="reserva.php">Reserva tu tour</a>
    <?php else: ?>
        <a href="login.php">Inicia sesión para reservar</a>
    <?php endif; ?>
</section>

<section class="destinos">
    <?php while($row = $destinos->fetch_assoc()): ?>
        <div class="tarjeta">
            <h4><?php echo $row['ciudad']; ?></h4>
            <p>Hotel: <?php echo $row['hotel']; ?></p>
            <p>Costo por persona: $<?php echo $row['costo']; ?></p>
            <?php if(isset($_SESSION['id_usuario'])): ?>
                <a href="reserva.php">Reservar</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</section>

<?php include 'views/footer.php'; ?>
