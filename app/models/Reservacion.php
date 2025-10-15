<?php
class Reservacion
{
    private $conn;
    private $table = "reservaciones";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // [MÉTODOS createReservacion, getAllReservaciones, getReservacionesByUsuario, getReservacionFullDataById, updateReservacion - MANTENIDOS]

    // Crear nueva reservación (¡Correcto y Seguro!)
    public function createReservacion($id_usuario, $id_destino, $fecha, $personas, $costo_total)
    {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (id_usuario, id_destino, fecha_reserva, numero_personas, costo_total) VALUES (?,?,?,?,?)");
        $stmt->bind_param("iisid", $id_usuario, $id_destino, $fecha, $personas, $costo_total);
        return $stmt->execute();
    }

    // Obtener todas las reservaciones (para admin) (Seguro por ser consulta estática)
    public function getAllReservaciones()
    {
        $query = "SELECT r.id_reservacion, u.nombre AS usuario, d.ciudad, d.hotel, r.fecha_reserva, r.numero_personas, r.costo_total
                     FROM reservaciones r
                     JOIN usuarios u ON r.id_usuario = u.id_usuario
                     JOIN destinos d ON r.id_destino = d.id_destino";
        return $this->conn->query($query);
    }

    // Obtener reservaciones por usuario (¡Correcto y Seguro!)
    public function getReservacionesByUsuario($id_usuario)
    {
        $stmt = $this->conn->prepare("SELECT r.id_reservacion, u.nombre AS usuario, d.ciudad, d.hotel, r.fecha_reserva, r.numero_personas, r.costo_total
                                     FROM reservaciones r
                                     JOIN usuarios u ON r.id_usuario = u.id_usuario
                                     JOIN destinos d ON r.id_destino = d.id_destino
                                     WHERE r.id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Obtener los datos completos de una reserva por ID
    public function getReservacionFullDataById($id_reservacion)
    {
        $stmt = $this->conn->prepare("SELECT id_reservacion, id_usuario, id_destino, fecha_reserva, numero_personas, costo_total
                                     FROM " . $this->table . " 
                                     WHERE id_reservacion = ?");
        $stmt->bind_param("i", $id_reservacion);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Actualizar una reservación existente
    public function updateReservacion($id_reservacion, $fecha_reserva, $numero_personas, $costo_total)
    {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " 
                                     SET fecha_reserva = ?, numero_personas = ?, costo_total = ? 
                                     WHERE id_reservacion = ?");
        $stmt->bind_param("sidi", $fecha_reserva, $numero_personas, $costo_total, $id_reservacion);
        return $stmt->execute();
    }

    // ===============================================
    // NUEVO MÉTODO: Eliminar reservación con control de propiedad
    // ===============================================

    /**
     * Elimina una reservación por ID, restringiendo opcionalmente por ID de usuario.
     * @param int $id_reservacion ID de la reserva a eliminar.
     * @param int $id_usuario ID del usuario propietario (o 0/null para admin bypass).
     * @return int|bool Retorna el número de filas afectadas (0 o 1) o false en caso de error.
     */
    public function deleteReservacion($id_reservacion, $id_usuario = 0)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_reservacion = ?";
        $types = "i";
        $params = [$id_reservacion];

        // Si el id_usuario es > 0, se añade la restricción de propiedad.
        // Esto garantiza que un usuario solo pueda eliminar su propia reserva.
        if ((int)$id_usuario > 0) {
            $query .= " AND id_usuario = ?";
            $types .= "i";
            $params[] = $id_usuario;
        }

        $stmt = $this->conn->prepare($query);

        // CORRECCIÓN: Usar call_user_func_array para bind_param con arrays dinámicos
        // Nota: mysqli::bind_param requiere parámetros pasados por referencia, 
        // por lo que se deben manejar cuidadosamente en el contexto de arrays.
        // Para simplicidad y seguridad, usaremos la sintaxis manual, ya que solo hay dos casos.

        if ((int)$id_usuario > 0) {
            $stmt->bind_param("ii", $id_reservacion, $id_usuario);
        } else {
            // Admin bypass
            $stmt->bind_param("i", $id_reservacion);
        }

        if (!$stmt->execute()) {
            return false; // Error de ejecución
        }

        return $stmt->affected_rows;
    }
}
