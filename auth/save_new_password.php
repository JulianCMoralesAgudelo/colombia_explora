<?php
include 'db.php';
include 'models/Usuario.php';

$usuarioModel = new Usuario($conn);
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    // Asumimos que se agregó el campo 'confirm_password' en la vista (Paso 21)
    $confirm_password = $_POST['confirm_password'] ?? ''; 
    
    // Asumimos que el token fue pasado también, ya que es necesario para eliminarlo de forma segura
    // Aunque el token no se pasó en el formulario, es más seguro eliminarlo por el token mismo.
    // Usaremos el correo temporalmente para la eliminación, pero la forma más segura es por token.

    // 1. Validaciones de la contraseña
    if (strlen($password) < 6) {
        $message = "La contraseña debe tener al menos 6 caracteres.";
    } elseif ($password !== $confirm_password) {
        $message = "Las contraseñas no coinciden.";
    } elseif (empty($correo)) {
        $message = "El correo electrónico es inválido. Intenta de nuevo desde el enlace.";
    } else {
        // 2. MVC: Actualizar la contraseña
        // La clase Usuario ya se encarga de aplicar password_hash de forma segura.
        if ($usuarioModel->updatePassword($correo, $password)) {
            
            // 3. MVC: Borrar tokens para que no se puedan reutilizar.
            // La eliminación por correo es menos segura que por token, pero se ajusta al contexto de tu formulario.
            // Si la columna 'email' de password_resets tiene un índice, usar el correo es viable.
            
            // NOTA: Para la máxima seguridad, deberías haber pasado el 'token' original en un campo oculto 
            // y eliminarlo usando $usuarioModel->deletePasswordResetToken($token_recibido)
            
            // Usamos la eliminación por correo, delegando al modelo si existe.
            if (method_exists($usuarioModel, 'deletePasswordResetsByEmail')) {
                 $usuarioModel->deletePasswordResetsByEmail($correo);
            } else {
                 // Si no existe el método, usamos la lógica MVC con un método existente (deletePasswordResetToken)
                 // Para simplificar, asumiremos que la eliminación por correo es un método que debes crear. 
                 // Por ahora, solo nos aseguramos de que la eliminación del token se haga.
                 // ¡ATENCIÓN!: Ya tienes el método deletePasswordResetToken que elimina por token. 
                 // Deberías modificar el formulario de reset_password para pasar el token también, o crear el método deletePasswordResetsByEmail.
                 
                 // Para terminar este flujo, simplemente borraremos todos los tokens asociados a ese email.
                 $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
                 $stmt->bind_param("s", $correo);
                 $stmt->execute();
            }

            $message = "✅ Contraseña actualizada correctamente. <a href='login.php'>Inicia sesión</a>";
            $success = true;
        } else {
            $message = "❌ Error al actualizar la contraseña.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de Recuperación</title>
</head>
<body>
    <h1>Restablecimiento de Contraseña</h1>
    <p><?php echo $message; ?></p>
</body>
</html>