<?php
// Incluir la conexión a DB
include 'db.php';
// Incluir el modelo Usuario (para guardar el token y verificar el correo)
include 'models/Usuario.php'; 

// URL base de tu sitio
$APP_URL = "http://localhost:8082";
$message = '';

// Instanciar el modelo Usuario
$usuarioModel = new Usuario($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);

    // ✅ CORREGIDO: getUsuarioByCorreo() devuelve array, no mysqli_result
    $usuario = $usuarioModel->getUsuarioByCorreo($correo);
    
    if (!$usuario) {
        // Por seguridad, mensaje genérico
        $message = "Si la dirección de correo electrónico es correcta, recibirás un enlace para restablecer tu contraseña.";
    } else {
        // ✅ CORREGIDO: El usuario existe, generar token
        $token = $usuarioModel->createPasswordResetToken($correo);

        if ($token) {
    // Construir enlace con tu dominio
    $link = $APP_URL . "/reset_password.php?token=" . urlencode($token);

    // Mostrar el enlace real para testing
    $message = "Se ha generado un enlace para restablecer tu contraseña.";
    $message .= "<br><br>Para restablecer tu contraseña, <a href='" . htmlspecialchars($link) . "'>haz clic aquí</a>";

} else {
     $message = "Ocurrió un error al generar el token. Inténtalo de nuevo.";
}

    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Viajes Colombia</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<?php include 'views/header.php'; ?>

<div class="main-container">
    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">🔑 Recuperar Contraseña</h2>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="correo">📧 Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" 
                           placeholder="Ingresa tu correo electrónico" 
                           value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>" 
                           required>
                </div>

                <button type="submit" class="login-btn-large">📨 Enviar Enlace de Recuperación</button>
            </form>

            <div class="login-links">
                <a href="login.php">← Volver al Login</a>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>
</body>
</html>