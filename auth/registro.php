<?php
// Incluir la conexión a DB
include 'db.php';
// Incluir el modelo Usuario (Controlador MVC)
include 'models/Usuario.php'; 
// Incluir el manejador de sesión compartido
include __DIR__ . '/../shared/session.php'; 

// La variable $message se usará para mostrar mensajes de éxito o error.
$message = '';
$usuarioModel = new Usuario($conn);

// Verificamos si la solicitud es de tipo POST (es decir, el formulario fue enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanear y validar las entradas del formulario para evitar ataques
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $correo = filter_var(trim($_POST['correo']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 2. Realizar validaciones básicas en el servidor
    if (!$correo) {
        $message = "El correo electrónico no es válido.";
    } elseif ($password !== $confirm_password) {
        $message = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 6) {
        $message = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Lógica MVC: Verificar si el correo ya existe en la base de datos
        $result = $usuarioModel->getUsuarioByCorreo($correo);
        
        if ($result->num_rows > 0) {
            $message = "Este correo ya está registrado.";
        } else {
            // 3. Hashear la contraseña de forma segura
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            
            // 4. Lógica MVC: Insertar el nuevo usuario.
            $success = $usuarioModel->createUsuario($nombre, $correo, $hash_password);

            if ($success) {
                // Registro exitoso, redirigimos al login
                header("Location: login.php?success=registered");
                exit();
            } else {
                $message = "Error al registrar el usuario.";
            }
        }
    }
}
?>

<?php include 'views/header.php'; ?>
<main>
    <form method="POST" action="">
        <h2>Registro de Usuario</h2>
        <input type="text" name="nombre" placeholder="Nombre completo" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" required>
        <input type="email" name="correo" placeholder="Correo electrónico" value="<?php echo htmlspecialchars($correo ?? ''); ?>" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
        <input type="submit" value="Registrarse">
        <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
        <hr>
        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
    </form>
</main>
<?php include 'views/footer.php'; ?>