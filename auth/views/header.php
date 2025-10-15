<?php
session_start();
// Definir URL base si no está definida
$authBaseUrl = "/auth";
?>
<header class="modern-header">
    <div class="header-container">
        <div class="logo-section">
            <img src="/assets/img/logo.png" alt="Viajes Colombia" class="logo">
            <h1 class="brand-title">Viajes Colombia</h1>
        </div>
        <nav class="modern-nav">
            <a href="/index.php" class="nav-link">Inicio</a>
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <a href="/views/listar_reservaciones.php" class="nav-link">Mis Reservas</a>
                <a href="<?php echo $authBaseUrl; ?>/logout.php" class="nav-link logout-btn">Cerrar Sesión</a>
            <?php else: ?>
                <a href="<?php echo $authBaseUrl; ?>/login.php" class="nav-link login-btn">Login</a>
                <a href="<?php echo $authBaseUrl; ?>/registro.php" class="nav-link register-btn">Registrarse</a>
            <?php endif; ?>
        </nav>
    </div>
</header>