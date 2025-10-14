<?php
header('Content-Type: text/html; charset=utf-8');
include 'db.php';
include 'models/Destino.php'; 
include __DIR__ . '/../shared/session.php'; 

$destinoModel = new Destino($conn);
$result = $destinoModel->getDestinos(); 
$authUrl = AUTH_BASE_URL;
?>

<?php include 'views/header.php'; ?>

<div class="main-container">
    <section class="modern-banner">
        <img src="/assets/img/banner.jpg" alt="Banner Viajes Colombia" class="banner-img">
        <h2>Explora Colombia con nosotros</h2>
        <p>Descubre los destinos más increíbles de nuestro país</p>
        <?php if(isset($_SESSION['id_usuario'])): ?>
            <a href="/reserva.php" class="cta-button">✨ Reserva tu tour</a>
        <?php else: ?>
            <a href="<?php echo htmlspecialchars($authUrl); ?>/login.php" class="cta-button">🚀 Inicia sesión para reservar</a>
        <?php endif; ?>
    </section>

    <section class="destinos-modern">
        <h3 class="section-title">🎯 Nuestros Destinos Populares</h3>
        <div class="destinos-grid">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="destino-card">
                    <div class="card-image">
                        <img src="/assets/img/<?php echo htmlspecialchars(strtolower($row['ciudad'])); ?>.jpg" 
                             alt="<?php echo htmlspecialchars($row['ciudad']); ?>" 
                             class="destino-img">
                    </div>
                    <div class="card-content">
                        <h4><?php echo htmlspecialchars($row['ciudad']); ?></h4>
                        <p class="hotel">🏨 <?php echo htmlspecialchars($row['hotel']); ?></p>
                        <p class="precio">$<?php echo number_format($row['costo'], 0, ',', '.'); ?> por persona</p>
                        <?php if(isset($_SESSION['id_usuario'])): ?>
                            <a href="/reserva.php" class="card-button">Reservar Ahora</a>
                        <?php else: ?>
                            <a href="<?php echo htmlspecialchars($authUrl); ?>/login.php" class="card-button">Iniciar Sesión</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</div>

<?php include 'views/footer.php'; ?>