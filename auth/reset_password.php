<?php
include 'db.php';
// Incluir el modelo Usuario (para la lógica de tokens)
include 'models/Usuario.php'; 

// Instanciar el modelo
$usuarioModel = new Usuario($conn);

$token = $_GET['token'] ?? '';
$error_message = '';
$correo = ''; // Variable para almacenar el correo si el token es válido

// 1. MVC: Usar el modelo para buscar y validar el token
$row = $usuarioModel->validatePasswordResetToken($token);

// 2. Validar el resultado del modelo
if (!$row) {
    // El modelo devuelve false si el token es inválido o expirado.
    $error_message = "Token inválido o expirado. Por favor, solicita un nuevo enlace de recuperación.";
} else {
    // Token válido. Guardamos el correo y borramos el error.
    $correo = htmlspecialchars($row['email']);
}

// Si hay un error, detenemos la ejecución y mostramos el mensaje.
if (!empty($error_message)) {
    // Puedes incluir una vista de error aquí si la tienes
    die("<h2>Error</h2><p>" . $error_message . "</p>");
}
?>

<h2>Restablecer contraseña</h2>
<form method="POST" action="save_new_password.php">
    <input type="hidden" name="correo" value="<?php echo $correo; ?>">

    <label>Nueva contraseña:</label><br>
    <input type="password" name="password" placeholder="Nueva contraseña" required><br><br>
    
    <label>Confirmar contraseña:</label><br>
    <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required><br><br>

    <input type="submit" value="Guardar">
</form>