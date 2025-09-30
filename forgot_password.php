<?php
include 'db.php';

// URL base de tu sitio (ajusta aquí si cambias de dominio)
$APP_URL = "https://colombiaViaja.page.gd";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);

    // Generar token único y fecha de expiración (30 min)
    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime("+30 minutes"));

    // Guardar en la tabla de reseteo de contraseñas
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $correo, $token, $expires);
    $stmt->execute();

    // Construir enlace con tu dominio
    $link = $APP_URL . "/reset_password.php?token=" . urlencode($token);

    // Aquí deberías enviar el correo real con mail() o PHPMailer
    // Por ahora solo mostramos el enlace en pantalla
    echo "Se envió un enlace a tu correo: <a href='$link'>$link</a>";
}
?>

<form method="POST">
    <h2>Recuperar contraseña</h2>
    <input type="email" name="correo" placeholder="Correo" required>
    <input type="submit" value="Enviar enlace">
</form>
