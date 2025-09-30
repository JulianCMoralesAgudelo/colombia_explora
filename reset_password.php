<?php
include 'db.php';

$token = $_GET['token'] ?? '';

// Buscar el token en la base de datos
$stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Validar token y fecha de expiración
if (!$row || strtotime($row['expires_at']) < time()) {
    die("Token inválido o expirado");
}

// Guardamos el correo en una variable segura
$correo = htmlspecialchars($row['email']);
?>

<h2>Restablecer contraseña</h2>
<form method="POST" action="save_new_password.php">
    <!-- Pasamos el correo oculto -->
    <input type="hidden" name="correo" value="<?php echo $correo; ?>">

    <label>Nueva contraseña:</label><br>
    <input type="password" name="password" placeholder="Nueva contraseña" required><br><br>

    <input type="submit" value="Guardar">
</form>
