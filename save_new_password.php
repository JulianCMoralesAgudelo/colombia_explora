<?php
include 'db.php';
include 'models/Usuario.php';

$usuario = new Usuario($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Pasamos la contraseña tal cual, la clase Usuario se encarga de aplicar password_hash
    if ($usuario->updatePassword($correo, $password)) {
        // Borrar tokens para que no se puedan reutilizar
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();

        echo "✅ Contraseña actualizada correctamente. <a href='login.php'>Inicia sesión</a>";
    } else {
        echo "❌ Error al actualizar la contraseña.";
    }
}
?>
