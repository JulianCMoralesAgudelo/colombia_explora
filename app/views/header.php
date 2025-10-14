<?php
if (!defined('AUTH_BASE_URL')) {
    define('AUTH_BASE_URL', 'http://192.168.1.100:8082');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viajes Colombia</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<header class="modern-header">
    <div class="header-container">
        <div class="logo-section">
            <img src="/assets/img/logo.png" alt="Viajes Colombia" class="logo">
            <h1 class="brand-title">Viajes Colombia</h1>
        </div>
        <nav class="modern-nav">
            <a href="/index.php" class="nav-link">Inicio</a>
            <?php if(isset($_SESSION['id_usuario'])): ?>
                <a href="/reserva.php" class="nav-link">Reservar</a>
                <a href="/views/listar_reservaciones.php" class="nav-link">Mis Reservaciones</a>
                <a href="<?php echo AUTH_BASE_URL; ?>/logout.php" class="nav-link logout-btn">Cerrar Sesión</a>
            <?php else: ?>
                <a href="<?php echo AUTH_BASE_URL; ?>/login.php" class="nav-link login-btn">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>