<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viajes Colombia</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<header>
    <img src="assets/img/logo.png" alt="Viajes Colombia" class="logo">
    <nav>
        <a href="index.php">Inicio</a>
        <?php if(isset($_SESSION['id_usuario'])): ?>
            <a href="reserva.php">Reservar</a>
            <a href="listar.php">Mis Reservaciones</a>
            <a href="logout.php">Cerrar Sesión</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>