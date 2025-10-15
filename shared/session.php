<?php
// ==========================================
// Módulo compartido de sesión (app + auth)
// ==========================================

// 1. CORRECCIÓN CRÍTICA: FORZAR ALMACENAMIENTO COMPARTIDO DE SESIÓN
$session_save_path = '/shared/sessions'; 

// Crear el directorio si no existe. Usamos @ para suprimir el Warning de permisos si falla.
if (!@is_dir($session_save_path)) {
    @mkdir($session_save_path, 0777, true);
}

// Configurar PHP para usar el directorio compartido. Usamos @ para suprimir warnings.
@session_save_path($session_save_path);


// 2. CONFIGURACIÓN DE COOKIE (Para compartir entre puertos)
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', 
    'secure' => false, 
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Definir la URL base para el contenedor de autenticación (AUTH)
define('AUTH_BASE_URL', getenv('AUTH_BASE_URL') ?: 'http://localhost:8082');

// 3. Iniciar sesión. Usamos @session_start() para prevenir el error 'Headers already sent'.
if (@session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// ==============================
// Funciones comunes
// ==============================

// Verifica si hay usuario autenticado
function checkLogin() {
    if (!isset($_SESSION['id_usuario'])) {
        $current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $redirect_url = urlencode($current_url);
        $login_url = AUTH_BASE_URL . "/login.php?redirect=" . $redirect_url;
        header("Location: " . $login_url);
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['rol']) && (int)$_SESSION['rol'] === 1;
}

function currentUser() {
    return $_SESSION['nombre'] ?? 'Invitado';
}

function logout() {
    session_unset();
    session_destroy();
    header("Location: " . AUTH_BASE_URL . "/login.php");
    exit();
}
