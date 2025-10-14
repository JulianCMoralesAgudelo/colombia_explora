<?php
// Incluir la conexión a DB
include 'db.php';
// Incluir el modelo Usuario (para guardar el token y verificar el correo)
include 'models/Usuario.php'; 

// URL base de tu sitio (se deja para la construcción del email, aunque la lógica del controlador es lo importante)
$APP_URL = "http://192.168.1.100:8082"; // Usamos la URL del contenedor auth para los enlaces internos.
$message = '';

// Instanciar el modelo Usuario
$usuarioModel = new Usuario($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);

    // 1. MVC: Verificar que el correo exista antes de generar el token (Opcional, pero mejor para seguridad)
    $result = $usuarioModel->getUsuarioByCorreo($correo);
    
    if ($result->num_rows === 0) {
        // Por seguridad, siempre es mejor no informar si el correo existe o no,
        // pero para debug y UX, mostraremos un mensaje genérico.
        $message = "Si la dirección de correo electrónico es correcta, recibirás un enlace para restablecer tu contraseña.";
    } else {
        // 2. MVC: Generar y Guardar token en el modelo
        // El modelo se encargará de generar el token, la expiración y la inserción segura.
        $token = $usuarioModel->createPasswordResetToken($correo);

        if ($token) {
            // 3. Construir enlace con tu dominio
            $link = $APP_URL . "/reset_password.php?token=" . urlencode($token);

            // Aquí debería ir la lógica de envío de correo
            // Por ahora, para testing, mostramos el mensaje.
            $message = "Se ha enviado un enlace para restablecer tu contraseña a **" . htmlspecialchars($correo) . "**.";
            $message .= "<br><br>DEBUG ENLACE: <a href='" . htmlspecialchars($link) . "'>Haz clic aquí para restablecer</a>";

        } else {
             $message = "Ocurrió un error al generar el token. Inténtalo de nuevo.";
        }
    }
}
?>

<form method="POST">
    <h2>Recuperar contraseña</h2>
    <input type="email" name="correo" placeholder="Correo" required>
    <input type="submit" value="Enviar enlace">
    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</form>