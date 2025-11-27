-- Script para crear la tabla de soporte técnico
USE perunet;

-- Tabla de solicitudes de soporte técnico
CREATE TABLE IF NOT EXISTS soporte_tecnico (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  tipo_servicio ENUM('instalacion_camaras', 'mantenimiento', 'soporte_tecnico', 'configuracion_redes', 'otro') NOT NULL,
  descripcion TEXT NOT NULL,
  fecha_preferida DATE NOT NULL,
  hora_preferida TIME NOT NULL,
  telefono_contacto VARCHAR(15) NOT NULL,
  direccion TEXT NOT NULL,
  estado ENUM('pendiente', 'aceptada', 'rechazada', 'en_proceso', 'completada') DEFAULT 'pendiente',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  notas_admin TEXT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_us) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
