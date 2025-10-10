<?php include 'views/header.php'; ?>

<main>
    <form method="POST" action="login.php">
        <h2>Login</h2>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="submit" value="Ingresar">

        <?php if (!empty($message)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <p>
            <a href="forgot_password.php">¿Olvidaste tu contraseña?</a>
        </p>
        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </form>
</main>

<?php include 'views/footer.php'; ?>
