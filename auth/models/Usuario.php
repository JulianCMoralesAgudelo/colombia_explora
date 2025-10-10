<?php
class Usuario
{
    private $conn;
    private $table = "usuarios";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Obtener usuario por correo
    public function getUsuarioByCorreo($correo)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Crear usuario nuevo
    public function createUsuario($nombre, $correo, $password, $rol)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (nombre, correo, password, rol) VALUES (?,?,?,?)");
        $stmt->bind_param("sssi", $nombre, $correo, $hash, $rol);
        return $stmt->execute();
    }

    // Obtener usuario por id
    public function getUsuarioById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }


    // Actualizar contraseña por correo
    public function updatePassword($correo, $newPassword)
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET password = ? WHERE correo = ?");
        $stmt->bind_param("ss", $hash, $correo);
        return $stmt->execute();
    }
}
