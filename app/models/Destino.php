<?php
class Destino {
    private $conn;
    private $table = "destinos";

    public function __construct($db){
        $this->conn = $db;
    }

    // Obtener todos los destinos
    public function getDestinos(){
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    // Obtener destino por id
    public function getDestinoById($id){
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id_destino = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
