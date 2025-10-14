<?php
class Usuario
{
    private $conn;
    private $table = "usuarios";
    private $resetTable = "password_resets"; 

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // [MÉTODOS ANTERIORES - MANTENIDOS]

    // Obtener usuario por correo
 // Obtener usuario por correo - CORREGIDO
public function getUsuarioByCorreo($correo)
{
    $stmt = $this->conn->prepare("SELECT id_usuario, nombre, password, id_rol FROM " . $this->table . " WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();  // ✅ Devuelve array, no mysqli_result
}

    // Crear usuario nuevo
    public function createUsuario($nombre, $correo, $hash_password)
    {
        $rol_por_defecto = 2; 
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (nombre, correo, password, id_rol) VALUES (?,?,?,?)");
        $stmt->bind_param("sssi", $nombre, $correo, $hash_password, $rol_por_defecto);
        return $stmt->execute();
    }
    
    // Genera y guarda un token de reseteo de contraseña
    public function createPasswordResetToken($correo)
    {
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        // Opcional pero recomendado: Borrar tokens anteriores para este correo (Usando bind_param)
        $stmt_del = $this->conn->prepare("DELETE FROM " . $this->resetTable . " WHERE email = ?");
        $stmt_del->bind_param("s", $correo);
        $stmt_del->execute();
        
        // Guardar el nuevo token con sentencia preparada
        $stmt = $this->conn->prepare("INSERT INTO " . $this->resetTable . " (email, token, expires_at) VALUES (?, ?, ?)");
        
        if (!$stmt) { return false; }

        $stmt->bind_param("sss", $correo, $token, $expires);
        
        if ($stmt->execute()) {
            return $token; 
        } else {
            return false;
        }
    }

    // NUEVO MÉTODO: Busca el token y valida si está expirado
    /**
     * @param string $token El token de reseteo a buscar.
     * @return array|bool Retorna la fila (email, expires_at) si es válido, o false si es inválido/expirado.
     */
    public function validatePasswordResetToken($token)
    {
        $stmt = $this->conn->prepare("SELECT email, expires_at FROM " . $this->resetTable . " WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Si no hay fila O la fecha de expiración ya pasó
        if (!$row || strtotime($row['expires_at']) < time()) {
            return false;
        }

        return $row; // Retorna la fila con email y expiración
    }

    // NUEVO MÉTODO: Elimina el token una vez que se ha usado la contraseña
    public function deletePasswordResetToken($token)
    {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->resetTable . " WHERE token = ?");
        $stmt->bind_param("s", $token);
        return $stmt->execute();
    }

    // Obtener usuario por id (Mantenido)
    public function getUsuarioById($id)
    {
        $stmt = $this->conn->prepare("SELECT id_usuario, nombre, id_rol FROM " . $this->table . " WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Actualizar contraseña por correo (Mantenido)
    public function updatePassword($correo, $newPassword)
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET password = ? WHERE correo = ?");
        $stmt->bind_param("ss", $hash, $correo);
        return $stmt->execute();
    }
}
?>