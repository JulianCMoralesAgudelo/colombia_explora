<div class="main-container">
    <section class="modern-banner">
        <img src="/assets/img/banner.jpg" alt="Banner Viajes Colombia" class="banner-img">
        <h2>Explora Colombia con nosotros</h2>
        <p>Descubre los destinos más increíbles de nuestro país</p>
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <a href="/reserva.php" class="cta-button">✨ Reserva tu tour</a>
        <?php else: ?>
            <a href="/auth/login.php" class="cta-button">🚀 Inicia sesión para reservar</a>
        <?php endif; ?>
    </section>

    <!-- ... resto del código ... -->

    <div class="card-content">
        <!-- ... -->
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <a href="/reserva.php" class="card-button">Reservar Ahora</a>
        <?php else: ?>
            <a href="/auth/login.php" class="card-button">Iniciar Sesión</a>
        <?php endif; ?>
    </div>
</div>