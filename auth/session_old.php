<?php
// Inicia la sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkLogin() {
    if(!isset($_SESSION['id_usuario'])) {
        header("Location: login.php");
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

function getUsuarioNombre() {
    return isset($_SESSION['nombre']) ? $_SESSION['nombre'] : null;
}
?>