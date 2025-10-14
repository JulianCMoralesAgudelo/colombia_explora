<?php
// Incluir session compartida
include __DIR__ . '/../shared/session.php';

// Incluir conexión a BD y modelos
include 'db.php';
include 'models/Usuario.php';

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    
    $usuarioModel = new Usuario($conn);
    $usuario = $usuarioModel->getUsuarioByCorreo($correo);
    
    // Verificar que el usuario existe y la contraseña es correcta
    if ($usuario && isset($usuario['password']) && password_verify($contrasena, $usuario['password'])) {
        // Login exitoso
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['id_rol'];
        
        // Redirigir a la página principal - AHORA CON SESIÓN COMPARTIDA
        header("Location: http://192.168.1.100:8080/index.php");
        exit();
    } else {
        // Login fallido
        header("Location: login.php?error=Credenciales+incorrectas");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Viajes Colombia</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<?php include 'views/header.php'; ?>

<div class="main-container">
    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">🔐 Iniciar Sesión</h2>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    ❌ <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="correo">📧 Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required 
                           placeholder="tu@email.com" value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="contrasena">🔒 Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required 
                           placeholder="Tu contraseña">
                </div>

                <button type="submit" class="login-btn-large">🚀 Ingresar</button>
            </form>

            <div class="login-links">
                <a href="forgot_password.php">¿Olvidaste tu contraseña?</a>
                <a href="registro.php">¿No tienes una cuenta? Regístrate aquí</a>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>
</body>
</html>