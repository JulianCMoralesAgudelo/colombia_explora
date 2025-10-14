<?php
include 'db.php';
include 'models/Usuario.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    
    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($contrasena) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $usuarioModel = new Usuario($conn);
        
        // ✅ CORREGIDO: getUsuarioByCorreo devuelve array, no mysqli_result
        $usuarioExistente = $usuarioModel->getUsuarioByCorreo($correo);
        
        if ($usuarioExistente) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            // Crear nuevo usuario
            $hash_password = password_hash($contrasena, PASSWORD_DEFAULT);
            $success = $usuarioModel->createUsuario($nombre, $correo, $hash_password);
            
            if ($success) {
                $mensaje = "✅ Registro exitoso. Ahora puedes iniciar sesión.";
                // Redirigir después de 2 segundos
                header("Refresh: 2; URL=login.php");
            } else {
                $error = "❌ Error al crear el usuario. Inténtalo de nuevo.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Viajes Colombia</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<?php include 'views/header.php'; ?>

<div class="main-container">
    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">👤 Crear Cuenta</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($mensaje): ?>
                <div class="alert alert-success">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="nombre">👤 Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" 
                           placeholder="Tu nombre completo" 
                           value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="correo">📧 Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" 
                           placeholder="tu@email.com" 
                           value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="contrasena">🔒 Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" 
                           placeholder="Mínimo 6 caracteres" 
                           minlength="6" required>
                </div>

                <div class="form-group">
                    <label for="confirmar_contrasena">🔒 Confirmar Contraseña:</label>
                    <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" 
                           placeholder="Repite tu contraseña" 
                           minlength="6" required>
                </div>

                <button type="submit" class="login-btn-large">🚀 Registrarse</button>
            </form>

            <div class="login-links">
                <a href="login.php">¿Ya tienes cuenta? Inicia Sesión</a>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>
</body>
</html>