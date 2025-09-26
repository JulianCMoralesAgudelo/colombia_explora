<?php
include 'db.php';
include 'session.php'; // Incluimos session.php para tener acceso a las funciones de sesión

// La variable $message se usará para mostrar mensajes de éxito o error.
$message = '';

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
        // 3. Hashear la contraseña de forma segura
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 4. Verificar si el correo ya existe en la base de datos
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $message = "Este correo ya está registrado.";
        } else {
            // 5. Insertar el nuevo usuario con rol 'cliente'
            $rol = 1;
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $correo, $hash_password, $rol);

            if ($stmt->execute()) {
                // Registro exitoso, redirigimos al login o a la página principal
                header("Location: login.php?success=registered");
                exit();
            } else {
                $message = "Error al registrar el usuario: " . $conn->error;
            }
        }
        $stmt->close();
    }
}
?>

<?php include 'views/header.php'; ?>
<main>
    <form method="POST" action="">
        <h2>Registro de Usuario</h2>
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="correo" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
        <input type="submit" value="Registrarse">
        <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
        <hr>
        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
    </form>
</main>
<?php include 'views/footer.php'; ?>