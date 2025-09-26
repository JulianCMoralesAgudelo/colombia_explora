<?php
class Reservacion {
    private $conn;
    private $table = "reservaciones";

    public function __construct($db){
        $this->conn = $db;
    }

    // Crear nueva reservación
    public function createReservacion($id_usuario, $id_destino, $fecha, $personas, $costo_total){
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (id_usuario, id_destino, fecha_reserva, numero_personas, costo_total) VALUES (?,?,?,?,?)");
        $stmt->bind_param("iisid", $id_usuario, $id_destino, $fecha, $personas, $costo_total);
        return $stmt->execute();
    }

    // Obtener todas las reservaciones (para admin)
    public function getAllReservaciones(){
        $query = "SELECT r.id_reservacion, u.nombre AS usuario, d.ciudad, d.hotel, r.fecha_reserva, r.numero_personas, r.costo_total
                  FROM reservaciones r
                  JOIN usuarios u ON r.id_usuario = u.id_usuario
                  JOIN destinos d ON r.id_destino = d.id_destino";
        return $this->conn->query($query);
    }

    // Obtener reservaciones por usuario
    public function getReservacionesByUsuario($id_usuario){
        $stmt = $this->conn->prepare("SELECT r.id_reservacion, u.nombre AS usuario, d.ciudad, d.hotel, r.fecha_reserva, r.numero_personas, r.costo_total
                                      FROM reservaciones r
                                      JOIN usuarios u ON r.id_usuario = u.id_usuario
                                      JOIN destinos d ON r.id_destino = d.id_destino
                                      WHERE r.id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
