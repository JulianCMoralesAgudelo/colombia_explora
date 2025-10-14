<?php
include 'db.php';
include 'models/Usuario.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    
    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($contrasena) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $usuarioModel = new Usuario($conn);
        
        // ✅ CORREGIDO: getUsuarioByCorreo devuelve array, no mysqli_result
        $usuarioExistente = $usuarioModel->getUsuarioByCorreo($correo);
        
        if ($usuarioExistente) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            // Crear nuevo usuario
            $hash_password = password_hash($contrasena, PASSWORD_DEFAULT);
            $success = $usuarioModel->createUsuario($nombre, $correo, $hash_password);
            
            if ($success) {
                $mensaje = "✅ Registro exitoso. Ahora puedes iniciar sesión.";
                // Redirigir después de 2 segundos
                header("Refresh: 2; URL=login.php");
            } else {
                $error = "❌ Error al crear el usuario. Inténtalo de nuevo.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Viajes Colombia</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <style>
        .login-modern {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .login-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('/assets/img/banner.jpg') center/cover;
            opacity: 0.1;
            z-index: 0;
        }

        .login-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-glass:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .login-title {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 1rem;
        }

        .login-form-modern .form-group {
            margin-bottom: 1.5rem;
        }

        .login-form-modern label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-modern {
            width: 100%;
            padding: 1rem 1.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .input-modern:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .input-modern::placeholder {
            color: #9ca3af;
        }

        .btn-login-modern {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1.2rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-login-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .btn-login-modern:active {
            transform: translateY(-1px);
        }

        .btn-login-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login-modern:hover::before {
            left: 100%;
        }

        .login-links-modern {
            margin-top: 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            border-top: 1px solid #e5e7eb;
            padding-top: 1.5rem;
        }

        .login-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }

        .login-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .login-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: all 0.3s ease;
        }

        .login-link:hover::after {
            width: 100%;
            left: 0;
        }

        .alert-modern {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .alert-error {
            background: linear-gradient(135deg, #fed7d7, #feb2b2);
            color: #c53030;
        }

        @media (max-width: 480px) {
            .login-glass {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .login-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
<div class="login-modern">
    <div class="login-glass">
        <div class="login-header">
            <div class="login-logo">👤</div>
            <h1 class="login-title">Crear Cuenta</h1>
            <p class="login-subtitle">Únete a Viajes Colombia</p>
        </div>

        <?php if ($error): ?>
            <div class="alert-modern alert-error">
                ⚠️ <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($mensaje): ?>
            <div class="alert-modern alert-success">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="login-form-modern">
            <div class="form-group">
                <label for="nombre">👤 Nombre Completo</label>
                <input type="text" id="nombre" name="nombre" 
                       class="input-modern"
                       placeholder="Tu nombre completo" 
                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="correo">📧 Correo Electrónico</label>
                <input type="email" id="correo" name="correo" 
                       class="input-modern"
                       placeholder="tu@email.com" 
                       value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="contrasena">🔒 Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" 
                       class="input-modern"
                       placeholder="Mínimo 6 caracteres" 
                       minlength="6" required>
            </div>

            <div class="form-group">
                <label for="confirmar_contrasena">🔒 Confirmar Contraseña</label>
                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" 
                       class="input-modern"
                       placeholder="Repite tu contraseña" 
                       minlength="6" required>
            </div>

            <button type="submit" class="btn-login-modern">
                🚀 Registrarse
            </button>
        </form>

        <div class="login-links-modern">
            <a href="login.php" class="login-link">¿Ya tienes cuenta? Inicia Sesión</a>
            <a href="http://192.168.1.100:8080/index.php" class="login-link">🏠 Volver al Inicio</a>
        </div>
    </div>
</div>
</body>
</html>