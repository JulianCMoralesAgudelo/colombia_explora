<?php include 'views/header.php'; ?>

<main>
    <form method="POST" action="login.php">
        <h2>Login</h2>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="submit" value="Ingresar">
        <p style="color:red;"><?php echo $message ?? ''; ?></p>
    </form>
</main>

<?php include 'views/footer.php'; ?>
