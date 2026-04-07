<?php
class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Crear solicitud pendiente
    public function crear($tallerId, $usuarioId)
    {
        // Verificar si ya tiene solicitud pendiente o aprobada para este taller
        $query = "SELECT id FROM solicitudes WHERE taller_id = ? AND usuario_id = ? AND estado IN ('pendiente', 'aprobada')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return ['success' => false, 'error' => 'Ya tienes una solicitud activa para este taller.'];
        }

        // Verificar cupo disponible
        $query = "SELECT cupo_disponible FROM talleres WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $tallerId);
        $stmt->execute();
        $taller = $stmt->get_result()->fetch_assoc();

        if (!$taller || $taller['cupo_disponible'] <= 0) {
            return ['success' => false, 'error' => 'No hay cupos disponibles para este taller.'];
        }

        // Insertar solicitud
        $query = "INSERT INTO solicitudes (taller_id, usuario_id, estado) VALUES (?, ?, 'pendiente')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return ['success' => true];
        }
        return ['success' => false, 'error' => 'Error al crear la solicitud.'];
    }

    // Obtener solicitudes pendientes con datos del taller y usuario
    public function getPendientes()
    {
        $query = "SELECT s.id, s.fecha_solicitud, s.estado, 
                         t.nombre AS taller_nombre, t.cupo_disponible,
                         u.username AS usuario_nombre
                  FROM solicitudes s
                  INNER JOIN talleres t ON s.taller_id = t.id
                  INNER JOIN usuarios u ON s.usuario_id = u.id
                  WHERE s.estado = 'pendiente'
                  ORDER BY s.fecha_solicitud ASC";
        $result = $this->conn->query($query);
        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        return $solicitudes;
    }

    // Aprobar solicitud
    public function aprobar($solicitudId)
    {
        // Obtener datos de la solicitud
        $query = "SELECT s.taller_id, s.estado FROM solicitudes s WHERE s.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $solicitudId);
        $stmt->execute();
        $solicitud = $stmt->get_result()->fetch_assoc();

        if (!$solicitud) {
            return false;
        }

        if ($solicitud['estado'] !== 'pendiente') {
            return false;
        }

        // Actualizar estado
        $query = "UPDATE solicitudes SET estado = 'aprobada' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $solicitudId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Rechazar solicitud
    public function rechazar($solicitudId)
    {
        $query = "UPDATE solicitudes SET estado = 'rechazada' WHERE id = ? AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $solicitudId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Obtener taller_id de una solicitud
    public function getTallerId($solicitudId)
    {
        $query = "SELECT taller_id FROM solicitudes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $solicitudId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ? $result['taller_id'] : null;
    }
}
