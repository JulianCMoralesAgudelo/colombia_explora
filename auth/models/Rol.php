<?php
class Rol {
    private $conn;
    private $table = "roles";

    public function __construct($db){
        $this->conn = $db;
    }

    // Obtener todos los roles
    public function getRoles(){
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Obtener un rol por id
    public function getRolById($id){
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id_rol = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
