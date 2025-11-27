<?php

class SoporteModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Crear nueva solicitud de soporte
     */
    public function createSolicitud($userId, $data)
    {
        try {
            $sql = "INSERT INTO soporte_tecnico 
                    (id_usuario, tipo_servicio, descripcion, fecha_preferida, hora_preferida, telefono_contacto, direccion) 
                    VALUES (:userId, :tipo_servicio, :descripcion, :fecha_preferida, :hora_preferida, :telefono, :direccion)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'userId' => $userId,
                'tipo_servicio' => $data['tipo_servicio'],
                'descripcion' => $data['descripcion'],
                'fecha_preferida' => $data['fecha_preferida'],
                'hora_preferida' => $data['hora_preferida'],
                'telefono' => $data['telefono_contacto'],
                'direccion' => $data['direccion']
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            throw new Exception('Error al crear solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Obtener solicitudes de un usuario
     */
    public function getSolicitudesByUser($userId)
    {
        $sql = "SELECT * FROM soporte_tecnico 
                WHERE id_usuario = :userId 
                ORDER BY fecha_creacion DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener todas las solicitudes (Admin)
     */
    public function getAllSolicitudes($estado = null)
    {
        $sql = "SELECT s.*, u.nombre, u.apellidos, u.correo 
                FROM soporte_tecnico s
                INNER JOIN usuario u ON s.id_usuario = u.id_us";
        
        if ($estado) {
            $sql .= " WHERE s.estado = :estado";
        }
        
        $sql .= " ORDER BY s.fecha_creacion DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($estado) {
            $stmt->execute(['estado' => $estado]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener solicitud por ID
     */
    public function getSolicitudById($id)
    {
        $sql = "SELECT s.*, u.nombre, u.apellidos, u.correo, u.telefono AS telefono_usuario, u.dni
                FROM soporte_tecnico s
                INNER JOIN usuario u ON s.id_usuario = u.id_us
                WHERE s.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualizar estado de solicitud
     */
    public function updateEstado($id, $estado, $notasAdmin = null)
    {
        try {
            $sql = "UPDATE soporte_tecnico 
                    SET estado = :estado, notas_admin = :notas 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'estado' => $estado,
                'notas' => $notasAdmin
            ]);
        } catch (Exception $e) {
            throw new Exception('Error al actualizar estado: ' . $e->getMessage());
        }
    }

    /**
     * Obtener solicitudes pendientes
     */
    public function getSolicitudesPendientes()
    {
        return $this->getAllSolicitudes('pendiente');
    }

    /**
     * Obtener estadísticas para dashboard admin
     */
    public function getEstadisticas()
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                    SUM(CASE WHEN estado = 'aceptada' THEN 1 ELSE 0 END) as aceptadas,
                    SUM(CASE WHEN estado = 'en_proceso' THEN 1 ELSE 0 END) as en_proceso,
                    SUM(CASE WHEN estado = 'completada' THEN 1 ELSE 0 END) as completadas,
                    SUM(CASE WHEN estado = 'rechazada' THEN 1 ELSE 0 END) as rechazadas
                FROM soporte_tecnico";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
