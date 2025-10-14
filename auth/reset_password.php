<?php
include 'db.php';
include 'models/Usuario.php';

$message = '';
$error = '';
$token = $_GET['token'] ?? '';

// Instanciar modelo
$usuarioModel = new Usuario($conn);

// Verificar token válido
$tokenData = $usuarioModel->validatePasswordResetToken($token);

if (!$tokenData) {
    $error = "El enlace de recuperación es inválido o ha expirado.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    
    // Validar que las contraseñas coincidan
    if ($nueva_contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($nueva_contrasena) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Actualizar contraseña
        $success = $usuarioModel->updatePassword($tokenData['email'], $nueva_contrasena);
        
        if ($success) {
            // Eliminar token usado
            $usuarioModel->deletePasswordResetToken($token);
            $message = "✅ Contraseña actualizada correctamente. Ahora puedes iniciar sesión.";
        } else {
            $error = "❌ Error al actualizar la contraseña. Inténtalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - Viajes Colombia</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<?php include 'views/header.php'; ?>

<div class="main-container">
    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">🔐 Nueva Contraseña</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($message): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                    <br><br>
                    <a href="login.php" class="login-btn-large">Iniciar Sesión</a>
                </div>
            <?php elseif ($tokenData): ?>
                <form method="POST" class="login-form">
                    <div class="form-group">
                        <label for="nueva_contrasena">🔒 Nueva Contraseña:</label>
                        <input type="password" id="nueva_contrasena" name="nueva_contrasena" 
                               placeholder="Mínimo 6 caracteres" 
                               minlength="6" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmar_contrasena">🔒 Confirmar Contraseña:</label>
                        <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" 
                               placeholder="Repite la contraseña" 
                               minlength="6" required>
                    </div>

                    <button type="submit" class="login-btn-large">🔄 Cambiar Contraseña</button>
                </form>
            <?php else: ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                    <br><br>
                    <a href="forgot_password.php" class="login-btn-large">Solicitar nuevo enlace</a>
                </div>
            <?php endif; ?>

            <div class="login-links">
                <a href="login.php">← Volver al Login</a>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>
</body>
</html>
