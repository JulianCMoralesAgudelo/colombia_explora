<?php
include 'db.php';
include 'session.php'; // Incluimos el archivo que gestiona las sesiones
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_usuario, nombre, password, rol FROM usuarios WHERE correo = ?");
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->bind_result($id, $nombre, $hash, $rol);
    $stmt->fetch();
    $stmt->close();

    if ($id && password_verify($password, $hash)) {
        $_SESSION['id_usuario'] = $id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['rol'] = $rol;
        header("Location: index.php");
        exit();
    } else {
        $message = "Correo o contraseña incorrectos";
    }
}
?>

<?php include 'views/header.php'; ?>
<main>
    <form method="POST" action="">
        <h2>Login</h2>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="submit" value="Ingresar">
        <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
        <hr>
        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </form>
</main>
<?php include 'views/footer.php'; ?>