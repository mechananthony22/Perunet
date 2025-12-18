-- ============================================
-- BASE DE DATOS COMPLETA PERUNET
-- Incluye: Tablas, Triggers, Vistas, Procedimientos y Datos
-- ============================================

DROP DATABASE IF EXISTS perunet;
CREATE DATABASE IF NOT EXISTS perunet;
USE perunet;

-- ============================================
-- TABLAS PRINCIPALES
-- ============================================

-- 1. ROLES
CREATE TABLE rol (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  estado ENUM('activo', 'suspendido') DEFAULT 'activo',
  create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. USUARIO GENERAL
CREATE TABLE usuario (
  id_us INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  correo VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  dni VARCHAR(8) NOT NULL UNIQUE,
  telefono VARCHAR(15),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_rol INT NOT NULL,
  estado ENUM('pendiente', 'activo') DEFAULT 'pendiente',
  codigo_verificacion VARCHAR(6) DEFAULT NULL,
  intentos_fallidos INT DEFAULT 0,
  bloqueo_until TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (id_rol) REFERENCES rol(id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. SUCURSAL
CREATE TABLE sucursal (
  id_sucur INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  direccion VARCHAR(255) NOT NULL,
  ciudad VARCHAR(100),
  departamento VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. CATEGORÍA
CREATE TABLE categoria (
  id_cat INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. SUBCATEGORÍA
CREATE TABLE subcategoria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  id_categoria INT,
  FOREIGN KEY (id_categoria) REFERENCES categoria(id_cat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 6. MARCA
CREATE TABLE marca (
  id_mar INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 7. MODELO
CREATE TABLE modelo (
  id_mod INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  id_marca INT,
  FOREIGN KEY (id_marca) REFERENCES marca(id_mar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 8. PRODUCTO
CREATE TABLE producto (
  id_pro INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  descripcion TEXT,
  precio DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  imagen VARCHAR(255),
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  id_subcategoria INT,
  id_marca INT,
  id_modelo INT,
  FOREIGN KEY (id_subcategoria) REFERENCES subcategoria(id),
  FOREIGN KEY (id_marca) REFERENCES marca(id_mar),
  FOREIGN KEY (id_modelo) REFERENCES modelo(id_mod)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 9. MÉTODO DE PAGO
CREATE TABLE metodo_pago (
  id_met INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  tipo ENUM('tarjeta', 'transferencia', 'monedero') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 10. DIRECCIÓN ENTREGA
CREATE TABLE direccion_entrega (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  departamento VARCHAR(50),
  provincia VARCHAR(50),
  distrito VARCHAR(50),
  calle VARCHAR(100),
  numero VARCHAR(10),
  piso VARCHAR(10),
  referencia TEXT,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_us)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 11. VENTA
CREATE TABLE venta (
  id_ven INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_direccion INT,
  total DECIMAL(10,2) NOT NULL,
  metodo_pago_id INT,
  tipo_entrega ENUM('recojo en tienda', 'domicilio') NOT NULL,
  id_sucursal INT NULL,
  estado ENUM('pendiente', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
  fecha_venta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_entrega TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_us),
  FOREIGN KEY (id_direccion) REFERENCES direccion_entrega(id),
  FOREIGN KEY (metodo_pago_id) REFERENCES metodo_pago(id_met),
  FOREIGN KEY (id_sucursal) REFERENCES sucursal(id_sucur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 12. DETALLE DE VENTA
CREATE TABLE detalle_venta (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_venta INT,
  id_producto INT,
  cantidad INT,
  precio_unitario DECIMAL(10,2),
  FOREIGN KEY (id_venta) REFERENCES venta(id_ven),
  FOREIGN KEY (id_producto) REFERENCES producto(id_pro)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 13. PAGO
CREATE TABLE pago (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_venta INT,
  numero_tarjeta VARCHAR(20),
  numero_telefono VARCHAR(15),
  FOREIGN KEY (id_venta) REFERENCES venta(id_ven)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 14. PROVEEDOR
CREATE TABLE proveedor (
  id_pro INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  ruc VARCHAR(11) NOT NULL,
  telefono VARCHAR(15),
  direccion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 15. CARRITO DE COMPRAS
CREATE TABLE carrito (
  id_carrito INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  estado ENUM('activo', 'abandonado', 'finalizado') DEFAULT 'activo',
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_us)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 16. DETALLE CARRITO
CREATE TABLE detalle_carrito (
  id_detalle INT AUTO_INCREMENT PRIMARY KEY,
  id_carrito INT NOT NULL,
  id_producto INT NOT NULL,
  cantidad INT NOT NULL DEFAULT 1,
  precio_total DECIMAL(10,2) NOT NULL,
  fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_carrito) REFERENCES carrito(id_carrito) ON DELETE CASCADE,
  FOREIGN KEY (id_producto) REFERENCES producto(id_pro),
  UNIQUE KEY uk_carrito_producto (id_carrito, id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 17. BUILDER CATEGORY
CREATE TABLE builder_category (
  id_cat INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  tipo ENUM('pc', 'setup') NOT NULL,
  descripcion TEXT,
  orden INT DEFAULT 0,
  requerido BOOLEAN DEFAULT FALSE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 18. BUILDER PRODUCT CATEGORY
CREATE TABLE builder_product_category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_producto INT NOT NULL,
  id_builder_category INT NOT NULL,
  FOREIGN KEY (id_producto) REFERENCES producto(id_pro) ON DELETE CASCADE,
  FOREIGN KEY (id_builder_category) REFERENCES builder_category(id_cat) ON DELETE CASCADE,
  UNIQUE KEY uk_product_builder_category (id_producto, id_builder_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 19. BUILDER CONFIGURATION
CREATE TABLE builder_configuration (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  nombre VARCHAR(150) NOT NULL,
  tipo ENUM('pc', 'setup') NOT NULL,
  configuracion TEXT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_us) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 20. SOPORTE TÉCNICO
CREATE TABLE soporte_tecnico (
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

-- ============================================
-- TABLAS DE SEGUIMIENTO DE PEDIDOS
-- ============================================

-- 21. SEGUIMIENTO DE PEDIDO
CREATE TABLE seguimiento_pedido (
  id_seguimiento INT AUTO_INCREMENT PRIMARY KEY,
  id_venta INT NOT NULL,
  estado_anterior ENUM('pendiente', 'confirmado', 'preparando', 'enviado', 'en_transito', 'entregado', 'cancelado') NULL,
  estado_nuevo ENUM('pendiente', 'confirmado', 'preparando', 'enviado', 'en_transito', 'entregado', 'cancelado') NOT NULL,
  comentario TEXT NULL,
  ubicacion VARCHAR(255) NULL COMMENT 'Ubicación actual del pedido',
  id_usuario_responsable INT NULL COMMENT 'Usuario que realizó el cambio',
  fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_venta) REFERENCES venta(id_ven) ON DELETE CASCADE,
  FOREIGN KEY (id_usuario_responsable) REFERENCES usuario(id_us) ON DELETE SET NULL,
  INDEX idx_venta (id_venta),
  INDEX idx_fecha (fecha_cambio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 22. ENVÍO DETALLE
CREATE TABLE envio_detalle (
  id_envio INT AUTO_INCREMENT PRIMARY KEY,
  id_venta INT NOT NULL UNIQUE,
  empresa_courier VARCHAR(100) NULL COMMENT 'Empresa de envío',
  numero_guia VARCHAR(50) NULL COMMENT 'Número de guía de rastreo',
  peso_kg DECIMAL(8,2) NULL COMMENT 'Peso del paquete en kg',
  dimensiones VARCHAR(50) NULL COMMENT 'Dimensiones (LxWxH)',
  costo_envio DECIMAL(10,2) NULL COMMENT 'Costo del envío',
  fecha_despacho TIMESTAMP NULL COMMENT 'Fecha de despacho',
  fecha_entrega_estimada DATE NULL COMMENT 'Fecha estimada de entrega',
  fecha_entrega_real TIMESTAMP NULL COMMENT 'Fecha real de entrega',
  nombre_receptor VARCHAR(150) NULL COMMENT 'Nombre del receptor',
  dni_receptor VARCHAR(8) NULL COMMENT 'DNI del receptor',
  observaciones TEXT NULL,
  FOREIGN KEY (id_venta) REFERENCES venta(id_ven) ON DELETE CASCADE,
  INDEX idx_numero_guia (numero_guia),
  INDEX idx_fecha_despacho (fecha_despacho)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 23. NOTIFICACIÓN SEGUIMIENTO
CREATE TABLE notificacion_seguimiento (
  id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
  id_venta INT NOT NULL,
  id_usuario INT NOT NULL,
  tipo_notificacion ENUM('email', 'sms', 'whatsapp', 'push') NOT NULL,
  mensaje TEXT NOT NULL,
  estado_envio ENUM('pendiente', 'enviado', 'fallido') DEFAULT 'pendiente',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_envio TIMESTAMP NULL,
  FOREIGN KEY (id_venta) REFERENCES venta(id_ven) ON DELETE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_us) ON DELETE CASCADE,
  INDEX idx_estado (estado_envio),
  INDEX idx_fecha (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- TRIGGERS AUTOMÁTICOS
-- ============================================

-- Trigger: Registrar seguimiento al crear venta
DELIMITER //
CREATE TRIGGER after_venta_insert
AFTER INSERT ON venta
FOR EACH ROW
BEGIN
  INSERT INTO seguimiento_pedido (id_venta, estado_anterior, estado_nuevo, comentario)
  VALUES (NEW.id_ven, NULL, NEW.estado, 'Pedido creado');
END//
DELIMITER ;

-- Trigger: Registrar seguimiento al actualizar venta
DELIMITER //
CREATE TRIGGER after_venta_update
AFTER UPDATE ON venta
FOR EACH ROW
BEGIN
  IF OLD.estado != NEW.estado THEN
    INSERT INTO seguimiento_pedido (id_venta, estado_anterior, estado_nuevo, comentario)
    VALUES (NEW.id_ven, OLD.estado, NEW.estado, 'Estado actualizado');
  END IF;
END//
DELIMITER ;

-- ============================================
-- VISTAS SQL
-- ============================================

-- Vista: Último seguimiento de cada pedido
CREATE OR REPLACE VIEW vista_ultimo_seguimiento AS
SELECT 
  s.id_venta,
  s.estado_nuevo AS estado_actual,
  s.comentario,
  s.ubicacion,
  s.fecha_cambio AS ultima_actualizacion,
  u.nombre AS responsable_nombre,
  u.apellidos AS responsable_apellidos
FROM seguimiento_pedido s
INNER JOIN (
  SELECT id_venta, MAX(id_seguimiento) AS ultimo_id
  FROM seguimiento_pedido
  GROUP BY id_venta
) ultimo ON s.id_seguimiento = ultimo.ultimo_id
LEFT JOIN usuario u ON s.id_usuario_responsable = u.id_us;

-- Vista: Pedidos completa
CREATE OR REPLACE VIEW vista_pedidos_completa AS
SELECT 
  v.id_ven,
  v.id_usuario,
  CONCAT(u.nombre, ' ', u.apellidos) AS cliente,
  u.correo AS cliente_correo,
  u.telefono AS cliente_telefono,
  v.total,
  v.tipo_entrega,
  v.estado AS estado_venta,
  v.fecha_venta,
  v.fecha_entrega,
  s.nombre AS sucursal,
  mp.nombre AS metodo_pago,
  de.departamento,
  de.provincia,
  de.distrito,
  CONCAT(de.calle, ' ', IFNULL(de.numero, ''), ' ', IFNULL(de.piso, '')) AS direccion_completa,
  de.referencia,
  ed.empresa_courier,
  ed.numero_guia,
  ed.fecha_despacho,
  ed.fecha_entrega_estimada,
  ed.fecha_entrega_real,
  uls.estado_actual AS ultimo_estado,
  uls.ultima_actualizacion,
  uls.ubicacion AS ubicacion_actual
FROM venta v
INNER JOIN usuario u ON v.id_usuario = u.id_us
LEFT JOIN sucursal s ON v.id_sucursal = s.id_sucur
LEFT JOIN metodo_pago mp ON v.metodo_pago_id = mp.id_met
LEFT JOIN direccion_entrega de ON v.id_direccion = de.id
LEFT JOIN envio_detalle ed ON v.id_ven = ed.id_venta
LEFT JOIN vista_ultimo_seguimiento uls ON v.id_ven = uls.id_venta;

-- ============================================
-- PROCEDIMIENTOS ALMACENADOS
-- ============================================

-- Procedimiento: Actualizar estado de pedido
DELIMITER //
CREATE PROCEDURE actualizar_estado_pedido(
  IN p_id_venta INT,
  IN p_nuevo_estado ENUM('pendiente', 'confirmado', 'preparando', 'enviado', 'en_transito', 'entregado', 'cancelado'),
  IN p_comentario TEXT,
  IN p_ubicacion VARCHAR(255),
  IN p_id_responsable INT
)
BEGIN
  DECLARE v_estado_actual ENUM('pendiente', 'enviado', 'entregado', 'cancelado');
  
  SELECT estado INTO v_estado_actual FROM venta WHERE id_ven = p_id_venta;
  
  UPDATE venta SET estado = p_nuevo_estado WHERE id_ven = p_id_venta;
  
  INSERT INTO seguimiento_pedido (
    id_venta, 
    estado_anterior, 
    estado_nuevo, 
    comentario, 
    ubicacion, 
    id_usuario_responsable
  ) VALUES (
    p_id_venta, 
    v_estado_actual, 
    p_nuevo_estado, 
    p_comentario, 
    p_ubicacion, 
    p_id_responsable
  );
END//
DELIMITER ;

-- Procedimiento: Obtener historial de pedido
DELIMITER //
CREATE PROCEDURE obtener_historial_pedido(IN p_id_venta INT)
BEGIN
  SELECT 
    s.id_seguimiento,
    s.estado_anterior,
    s.estado_nuevo,
    s.comentario,
    s.ubicacion,
    s.fecha_cambio,
    CONCAT(u.nombre, ' ', u.apellidos) AS responsable
  FROM seguimiento_pedido s
  LEFT JOIN usuario u ON s.id_usuario_responsable = u.id_us
  WHERE s.id_venta = p_id_venta
  ORDER BY s.fecha_cambio DESC;
END//
DELIMITER ;

-- Procedimiento: Registrar envío
DELIMITER //
CREATE PROCEDURE registrar_envio(
  IN p_id_venta INT,
  IN p_empresa_courier VARCHAR(100),
  IN p_numero_guia VARCHAR(50),
  IN p_peso_kg DECIMAL(8,2),
  IN p_dimensiones VARCHAR(50),
  IN p_costo_envio DECIMAL(10,2),
  IN p_fecha_entrega_estimada DATE
)
BEGIN
  INSERT INTO envio_detalle (
    id_venta,
    empresa_courier,
    numero_guia,
    peso_kg,
    dimensiones,
    costo_envio,
    fecha_despacho,
    fecha_entrega_estimada
  ) VALUES (
    p_id_venta,
    p_empresa_courier,
    p_numero_guia,
    p_peso_kg,
    p_dimensiones,
    p_costo_envio,
    NOW(),
    p_fecha_entrega_estimada
  )
  ON DUPLICATE KEY UPDATE
    empresa_courier = p_empresa_courier,
    numero_guia = p_numero_guia,
    peso_kg = p_peso_kg,
    dimensiones = p_dimensiones,
    costo_envio = p_costo_envio,
    fecha_entrega_estimada = p_fecha_entrega_estimada;
END//
DELIMITER ;

-- ============================================
-- DATOS INICIALES
-- ============================================

-- INSERTAR ROLES
INSERT INTO rol (nombre) VALUES
('admin'),
('cliente'),
('trabajador'),
('proveedor'),
('vendedor');

-- INSERTAR USUARIOS
INSERT INTO usuario (id_us, nombre, apellidos, correo, contrasena, dni, telefono, fecha_registro, id_rol, estado, codigo_verificacion)
VALUES
(1, 'Anthony', 'Mechan Parra', 'admin@uss.edu.pe', '$2y$10$123456789012345678901u1234567890abcd12345678abcde123', '17639560', '987654321', '2025-06-16 05:41:26', 1, 'activo', NULL),
(2, 'Carlos', 'Vega Torres', 'admin1@gmail.com', '$2y$10$1234567890ABCDEfghijk1234567890LMNOPQRSTUVabcde1234', '94653652', '912345678', '2025-06-16 05:50:46', 2, 'activo', NULL),
(3, 'Arni', 'Flores Ramos', 'arni21154@gmail.com', '$2y$10$9876543210QWERTYuiopasd9876543210zxcvb1234567lmnop', '77327894', '987456321', '2025-06-16 06:26:34', 1, 'activo', NULL),
(4, 'Arni', 'Flores Torres', 'arni215s4@gmail.com', '$2y$10$9999999999HHHHHHHHHHHHH9999999999jjjjjjjjjjjjjjjjj', '77327823', '945612378', '2025-06-16 07:06:12', 2, 'activo', NULL),
(5, 'Jose', 'Perez Mendez', 'sd@sad', '$2y$10$555555555555555555555555555555555555555555555555', '12323232', '963852741', '2025-06-16 07:06:59', 2, 'activo', NULL),
(6, 'Anthony', 'Mechan Parra', 'anthony@gmail.com', '$2y$10$Cw23Pn7.4ufjHw0V1sp/pe0RNF.htHW6xUN1F/9mIvuco8vmcZVe.', '77327896', '987654321', '2025-06-16 07:11:37', 1, 'activo', NULL),
(7, 'junior', 'usuario', 'usuario@gmail.com', '$2y$10$65mv1myKRkI2UwJawu.8sub2DF8P2EbSdc5EXV530pzX1ukJR9sby', '12323999', '963852741', '2025-06-16 07:11:37', 2, 'activo', NULL),
(8, 'jr', 'usuario 2', 'usuario2@gmail.com', '$2y$10$65mv1myKRkI2UwJawu.8sub2DF8P2EbSdc5EXV530pzX1ukJR9sby', '12323635', '963852869', '2025-06-16 07:11:37', 2, 'activo', NULL),
(9, 'admin', 'admin', 'admin@gmail.com', '$2y$10$65mv1myKRkI2UwJawu.8sub2DF8P2EbSdc5EXV530pzX1ukJR9sby', '12323000', '963852741', '2025-06-16 07:11:37', 1, 'activo', NULL);

-- INSERTAR DIRECCIÓN DE ENTREGA
INSERT INTO direccion_entrega (id_usuario, departamento, provincia, distrito, calle, numero, piso, referencia)
VALUES
(7, 'Lambayeque', 'Chiclayo', 'Chiclayo', 'Av. Chiclayo', '123', '1', 'Referencia 1');

-- INSERTAR CATEGORÍAS
INSERT INTO categoria (id_cat, nombre) VALUES 
(1, 'VIDEOVIGILANCIA'),
(2, 'GAMER'),
(3, 'ALMACENAMIENTO'),
(4, 'CABLEADO Y ESTRUCTURADO'),
(5, 'CONTROL DE ACCESO');

-- INSERTAR SUBCATEGORÍAS
INSERT IGNORE INTO subcategoria (id, nombre, id_categoria) VALUES 
(1, 'Cámaras IP', 1),
(2, 'Grabadores NVR', 1),
(3, 'Accesorios de Vigilancia', 1),
(4, 'Monitores de Seguridad', 1),
(5, 'Alarmas', 1),
(6, 'Teclados Gamer', 2),
(7, 'Mouses Gamer', 2),
(8, 'Parlantes Gamer', 2),
(9, 'Audífonos Gamer', 2),
(10, 'Pad Mouse', 2),
(11, 'Discos Duros Externos', 3),
(12, 'Memorias USB', 3),
(13, 'Memorias SD', 3),
(14, 'Discos SSD', 3),
(15, 'Discos HDD', 3),
(16, 'Cables UTP', 4),
(17, 'Conectores RJ45', 4),
(18, 'Patch Panels', 4),
(19, 'Cables Contra Incendios', 4),
(20, 'Canaletas', 4),
(21, 'Tags de Proximidad', 5),
(22, 'Intercomunicadores', 5),
(23, 'Cerraduras Inalámbricas', 5),
(24, 'Lector de Huella Digital', 5),
(25, 'Módulos de Control de Acceso', 5);

-- INSERTAR MARCAS
INSERT INTO marca (id_mar, nombre) VALUES 
(1, 'Hikvision'),
(2, 'Dahua'),
(3, 'Panduit'),
(4, 'Cybertel'),
(5, 'Halion'),
(6, 'Kingston'),
(7, 'Seagate'),
(8, 'Western Digital'),
(9, 'TP-Link'),
(10, 'Logitech');

-- INSERTAR MODELOS
INSERT INTO modelo (id_mod, nombre, id_marca) VALUES 
(1, 'DS-2CD2043G0', 1),
(2, 'DS-7608NI-K2', 1),
(3, 'DS-K1T804', 1),
(4, 'DS-7732NI-I4', 1),
(5, 'DS-2CE16D0T', 1),
(6, 'IPC-HFW1230S', 2),
(7, 'XVR5108HS', 2),
(8, 'ASR1102A', 2),
(9, 'DHI-VTH5221', 2),
(10, 'HAC-HDW1200EM', 2);

-- INSERTAR PRODUCTOS
INSERT INTO producto (nombre, descripcion, precio, stock, imagen, id_subcategoria, id_marca, id_modelo)
VALUES 
('Cámara IP Hikvision DS-2CD2043G0', 'Cámara de videovigilancia 4MP para exteriores con visión nocturna', 320.00, 25, 'VIDEOVIGILANCIA/CAMARAS/Cámara Anti-vandalismo.png', 1, 1, 1),
('Cámara IP Dahua IPC-HFW1230S', 'Cámara de vigilancia WiFi 1080P con audio integrado', 280.00, 30, 'VIDEOVIGILANCIA/CAMARAS/Cámara c.png', 1, 2, 6),
('NVR Hikvision DS-7608NI-K2', 'Grabador de video en red 8 canales 4K', 450.00, 15, 'VIDEOVIGILANCIA/NVR/NVR 16 Canales.png', 2, 1, 2),
('Monitor de Seguridad 19\"', 'Monitor profesional para videovigilancia', 180.00, 20, 'VIDEOVIGILANCIA/MONITORES/Monitor 1.png', 4, 1, 1),
('Cable Ethernet Cat6 100m', 'Cable de red para instalaciones de videovigilancia', 85.00, 50, 'VIDEOVIGILANCIA/ACCESORIOS DE VIGILANCIA/Cable Ethernet Cat6.png', 3, 3, 1),
('Teclado Gamer Logitech G Pro', 'Teclado mecánico RGB para gaming profesional', 120.00, 40, 'GAMER/TECLADOS/Teclado1.png', 6, 10, 1),
('Mouse Gamer Logitech G502 Hero', 'Mouse gaming con sensor HERO 25K', 95.00, 35, 'GAMER/mouse/Logitech G502 Hero.png', 7, 10, 2),
('Audífonos Gamer Corsair HS60', 'Audífonos gaming con micrófono desmontable', 75.00, 30, 'GAMER/AUDIFONOS/Audifonos1.png', 9, 10, 1),
('Parlantes Gamer 2.1', 'Sistema de audio gaming con subwoofer', 65.00, 25, 'GAMER/PARLANTES/Parlante1.png', 8, 10, 1),
('Pad Mouse Gamer RGB', 'Alfombrilla gaming con iluminación RGB', 25.00, 60, 'GAMER/PADMOUSE/PadMouse1.png', 10, 10, 1),
('SSD Kingston A2000 500GB', 'Disco sólido interno NVMe de alta velocidad', 85.00, 45, 'ALMACENAMIENTO/Memorias SD/Kingston A2000.png', 1, 6, 1),
('HDD Seagate Barracuda 1TB', 'Disco duro interno 7200 RPM', 45.00, 80, 'ALMACENAMIENTO/Discos HDD/Seagate Barracuda.png', 2, 7, 1),
('USB Kingston DataTraveler 32GB', 'Memoria USB de alta velocidad', 15.00, 100, 'ALMACENAMIENTO/Memorias USB/Kingston DataTraveler G4.png', 3, 6, 1),
('SD Card Kingston Canvas 64GB', 'Tarjeta de memoria para cámaras', 20.00, 75, 'ALMACENAMIENTO/Discos SSD/Kingston Canvas React.png', 4, 6, 1),
('Cable UTP Cat6 305m', 'Cable de red categoría 6 para instalaciones', 120.00, 30, 'CABLADO/UTP/Cable UTP Cat6.png', 1, 3, 1),
('Patch Panel 24 Puertos', 'Panel de conexión para cableado estructurado', 45.00, 25, 'CABLADO/PATCH/Patch Panel 24 Puertos.png', 2, 3, 1),
('Conector RJ45 Crimpado', 'Conectores RJ45 para terminación de cables', 2.50, 200, 'CABLADO/RJ45/Conector RJ45 Crimpado.png', 3, 3, 1),
('Canaleta PVC 2x2', 'Canaleta para organizar cables', 8.00, 150, 'CABLADO/CANALETAS/Canaleta de PVC.png', 4, 3, 1),
('Lector Biométrico Facial', 'Control de acceso con reconocimiento facial', 350.00, 15, 'CONTROL DE ACCESO/LECTOR/BIOMETRICO FACIAL DE ASISTENCIA UFACE800 PLUS.png', 1, 1, 3),
('Cerradura Electrónica WiFi', 'Cerradura inteligente con control remoto', 280.00, 20, 'CONTROL DE ACCESO/CERRADURAS/Cerradura de Seguridad Inalámbrica.png', 2, 1, 1),
('Tag de Proximidad 125kHz', 'Tarjetas de acceso por proximidad', 3.50, 500, 'CONTROL DE ACCESO/TAGS/Tag de Proximidad 125 kHz.png', 3, 1, 1),
('Controlador de Acceso 2 Puertas', 'Módulo de control para 2 puertas', 180.00, 25, 'CONTROL DE ACCESO/MODULO/Controlador de Acceso 2 Puertas.png', 4, 1, 1);

-- INSERTAR MÉTODOS DE PAGO
INSERT INTO metodo_pago (id_met, nombre, tipo) VALUES 
(1, 'Tarjeta', 'tarjeta'),
(2, 'Yape', 'monedero'),
(3, 'Plin', 'monedero');

-- INSERTAR SUCURSALES
INSERT INTO sucursal (nombre, direccion, ciudad, departamento) VALUES
('Sede 1', 'Av. Lima 123', 'Lima', 'Lima'),
('Sede 2', 'Jr. Cusco 456', 'Lima', 'Lima'),
('Sede 3', 'Calle Piura 789', 'Lima', 'Lima');

-- INSERTAR BUILDER CATEGORIES
INSERT INTO builder_category (nombre, tipo, descripcion, orden, requerido) VALUES
('Procesador', 'pc', 'Selecciona el procesador para tu PC', 1, TRUE),
('Placa Base', 'pc', 'Selecciona la placa base compatible', 2, TRUE),
('Memoria RAM', 'pc', 'Selecciona la memoria RAM', 3, TRUE),
('Almacenamiento', 'pc', 'Selecciona discos SSD o HDD', 4, TRUE),
('Tarjeta Gráfica', 'pc', 'Selecciona la tarjeta gráfica (GPU)', 5, FALSE),
('Fuente de Poder', 'pc', 'Selecciona la fuente de alimentación', 6, TRUE),
('Gabinete', 'pc', 'Selecciona el gabinete para tu PC', 7, TRUE),
('Refrigeración', 'pc', 'Selecciona el sistema de refrigeración', 8, FALSE),
('Escritorio', 'setup', 'Selecciona tu escritorio', 1, TRUE),
('Silla', 'setup', 'Selecciona tu silla gamer', 2, TRUE),
('Monitor', 'setup', 'Selecciona tu monitor', 3, TRUE),
('Teclado', 'setup', 'Selecciona tu teclado', 4, TRUE),
('Mouse', 'setup', 'Selecciona tu mouse', 5, TRUE),
('Audífonos', 'setup', 'Selecciona tus audífonos', 6, FALSE),
('Micrófono', 'setup', 'Selecciona tu micrófono', 7, FALSE),
('Webcam', 'setup', 'Selecciona tu cámara web', 8, FALSE),
('Iluminación', 'setup', 'Selecciona tu iluminación LED', 9, FALSE);

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
-- ================================================
-- INSERTS MASIVOS DE PRODUCTOS (subcategorías vacías)
-- ================================================

INSERT INTO producto (nombre, descripcion, precio, stock, imagen, id_subcategoria, id_marca, id_modelo) VALUES
-- Discos Duros Externos (id_subcategoria = 11)
('Seagate Expansion 1TB USB 3.0', 'Disco duro externo portátil 1TB USB 3.0', 199.00, 40, 'ALMACENAMIENTO/Discos Duros Externos/Seagate Expansion 1TB.png', 11, 7, 1),
('Western Digital My Passport 2TB', 'Disco externo portátil 2TB USB 3.2', 289.00, 35, 'ALMACENAMIENTO/Discos Duros Externos/WD My Passport 2TB.png', 11, 8, 1),
('Seagate One Touch 4TB', 'Disco externo 4TB One Touch USB 3.0', 499.00, 20, 'ALMACENAMIENTO/Discos Duros Externos/Seagate One Touch 4TB.png', 11, 7, 1),
('WD Elements 1TB', 'Disco portátil WD Elements 1TB', 189.00, 25, 'ALMACENAMIENTO/Discos Duros Externos/WD Elements 1TB.png', 11, 8, 1),
('Seagate Backup Plus Slim 2TB', 'Disco externo delgado Backup Plus Slim 2TB', 279.00, 30, 'ALMACENAMIENTO/Discos Duros Externos/Seagate Backup Plus Slim 2TB.png', 11, 7, 1),

-- Discos HDD internos (id_subcategoria = 15)
('Seagate Barracuda 1TB 7200RPM', 'HDD interno 1TB SATA 7200 RPM', 155.00, 60, 'ALMACENAMIENTO/Discos HDD/Seagate Barracuda 1TB.png', 15, 7, 1),
('Western Digital Blue 1TB', 'HDD interno WD Blue 1TB SATA', 165.00, 55, 'ALMACENAMIENTO/Discos HDD/WD Blue 1TB.png', 15, 8, 1),
('Seagate SkyHawk 2TB', 'HDD para videovigilancia 2TB', 235.00, 45, 'ALMACENAMIENTO/Discos HDD/Seagate SkyHawk 2TB.png', 15, 7, 1),
('WD Purple 1TB Surveillance', 'HDD WD Purple 1TB para cámaras', 210.00, 50, 'ALMACENAMIENTO/Discos HDD/WD Purple 1TB.png', 15, 8, 1),
('Seagate IronWolf 4TB', 'HDD NAS IronWolf 4TB', 389.00, 25, 'ALMACENAMIENTO/Discos HDD/Seagate IronWolf 4TB.png', 15, 7, 1),

-- Discos SSD (id_subcategoria = 14)
('SSD Kingston A400 480GB', 'SSD interno 480GB SATA III', 129.00, 70, 'ALMACENAMIENTO/Discos SSD/Kingston A400 480GB.png', 14, 6, 1),
('SSD WD Green 480GB', 'SSD interno WD Green 480GB SATA', 135.00, 60, 'ALMACENAMIENTO/Discos SSD/WD Green 480GB.png', 14, 8, 1),
('SSD Kingston NV2 1TB NVMe', 'SSD NVMe 1TB PCIe 4.0', 279.00, 40, 'ALMACENAMIENTO/Discos SSD/Kingston NV2 1TB.png', 14, 6, 1),
('SSD WD Blue SN570 1TB', 'SSD NVMe SN570 1TB', 299.00, 35, 'ALMACENAMIENTO/Discos SSD/WD SN570 1TB.png', 14, 8, 1),
('SSD Seagate FireCuda 530 1TB', 'SSD gaming FireCuda 1TB NVMe', 499.00, 20, 'ALMACENAMIENTO/Discos SSD/Seagate FireCuda 530 1TB.png', 14, 7, 1),

-- Memorias SD (id_subcategoria = 13)
('Kingston Canvas Select Plus 64GB SD', 'Tarjeta SD 64GB para cámaras y datos', 45.00, 80, 'ALMACENAMIENTO/Memorias SD/Kingston SD 64GB.png', 13, 6, 1),
('Kingston Canvas Go Plus 128GB SD', 'Tarjeta SD 128GB alta velocidad', 85.00, 60, 'ALMACENAMIENTO/Memorias SD/Kingston SD 128GB.png', 13, 6, 1),
('Kingston Canvas 256GB SD', 'Tarjeta SD 256GB durable', 149.00, 40, 'ALMACENAMIENTO/Memorias SD/Kingston SD 256GB.png', 13, 6, 1),
('SanDisk Ultra 64GB SD', 'Tarjeta SD 64GB económica', 55.00, 70, 'ALMACENAMIENTO/Memorias SD/SanDisk SD 64GB.png', 13, 6, 1),
('SanDisk Extreme Pro 128GB SD', 'Tarjeta SD 128GB profesional velocidad alta', 159.00, 30, 'ALMACENAMIENTO/Memorias SD/SanDisk SD 128GB.png', 13, 6, 1),

-- Memorias USB (id_subcategoria = 12)
('Kingston DataTraveler 32GB USB', 'Pendrive USB 3.2 32GB', 22.00, 150, 'ALMACENAMIENTO/Memorias USB/Kingston USB 32GB.png', 12, 6, 1),
('Kingston DataTraveler 64GB USB', 'Pendrive USB 64GB rápido', 35.00, 120, 'ALMACENAMIENTO/Memorias USB/Kingston USB 64GB.png', 12, 6, 1),
('Kingston DataTraveler 128GB USB', 'Pendrive USB 128GB alta capacidad', 59.00, 90, 'ALMACENAMIENTO/Memorias USB/Kingston USB 128GB.png', 12, 6, 1),
('SanDisk Cruzer Blade 32GB USB', 'Pendrive USB 32GB económico', 20.00, 200, 'ALMACENAMIENTO/Memorias USB/SanDisk USB 32GB.png', 12, 6, 1),
('SanDisk Ultra Flair 64GB USB', 'Pendrive USB metálico 64GB', 45.00, 110, 'ALMACENAMIENTO/Memorias USB/SanDisk USB 64GB.png', 12, 6, 1),

-- Cables Contra Incendios (id_subcategoria = 19)
('Cable Contra Incendios 50m', 'Cable ignífugo 50 metros para instalaciones', 120.00, 80, 'CABLADO/Cables Contra Incendios/Cable 50m.png', 19, 3, 1),
('Cable Contra Incendios 100m', 'Cable ignífugo 100 metros', 220.00, 60, 'CABLADO/Cables Contra Incendios/Cable 100m.png', 19, 3, 1),
('Cable Contra Incendios 150m', 'Cable ignífugo 150 metros', 330.00, 40, 'CABLADO/Cables Contra Incendios/Cable 150m.png', 19, 3, 1),
('Cable Contra Incendios 200m', 'Cable ignífugo 200 metros', 430.00, 30, 'CABLADO/Cables Contra Incendios/Cable 200m.png', 19, 3, 1),
('Cable Contra Incendios 250m', 'Cable ignífugo 250 metros', 530.00, 20, 'CABLADO/Cables Contra Incendios/Cable 250m.png', 19, 3, 1),

-- Cables UTP (id_subcategoria = 16)
('Cable UTP Cat6 305m', 'Bobina UTP Cat6 305 metros', 120.00, 50, 'CABLADO/UTP/Cable UTP Cat6 305m.png', 16, 3, 1),
('Cable UTP Cat6 100m', 'Bobina UTP Cat6 100 metros', 45.00, 80, 'CABLADO/UTP/Cable UTP Cat6 100m.png', 16, 3, 1),
('Cable UTP Cat6 50m', 'Bobina UTP Cat6 50 metros', 25.00, 120, 'CABLADO/UTP/Cable UTP Cat6 50m.png', 16, 3, 1),
('Cable UTP Cat5e 305m', 'Bobina UTP Cat5e 305 metros', 90.00, 40, 'CABLADO/UTP/Cable UTP Cat5e 305m.png', 16, 3, 1),
('Cable UTP Cat5e 100m', 'Bobina UTP Cat5e 100 metros', 35.00, 70, 'CABLADO/UTP/Cable UTP Cat5e 100m.png', 16, 3, 1),

-- Canaletas (id_subcategoria = 20)
('Canaleta PVC 2x2 1m', 'Canaleta PVC 2x2 de 1 metro', 8.00, 150, 'CABLADO/Canaletas/Canaleta PVC 1m.png', 20, 3, 1),
('Canaleta PVC 2x2 2m', 'Canaleta PVC 2x2 de 2 metros', 14.00, 120, 'CABLADO/Canaletas/Canaleta PVC 2m.png', 20, 3, 1),
('Canaleta PVC 2x2 3m', 'Canaleta PVC 2x2 de 3 metros', 20.00, 90, 'CABLADO/Canaletas/Canaleta PVC 3m.png', 20, 3, 1),
('Canaleta PVC 2x2 4m', 'Canaleta PVC 2x2 de 4 metros', 26.00, 60, 'CABLADO/Canaletas/Canaleta PVC 4m.png', 20, 3, 1),
('Canaleta PVC 2x2 5m', 'Canaleta PVC 2x2 de 5 metros', 32.00, 50, 'CABLADO/Canaletas/Canaleta PVC 5m.png', 20, 3, 1),

-- Conectores RJ45 (id_subcategoria = 17)
('Conector RJ45 Cat6 UTP', 'Conector RJ45 para cable UTP Cat6', 2.50, 200, 'CABLADO/RJ45/Conector RJ45 Cat6.png', 17, 3, 1),
('Conector RJ45 Cat5e UTP', 'Conector RJ45 para cable UTP Cat5e', 2.00, 250, 'CABLADO/RJ45/Conector RJ45 Cat5e.png', 17, 3, 1),
('Conector RJ45 Blindado STP', 'Conector RJ45 blindado para cable STP', 3.50, 150, 'CABLADO/RJ45/Conector RJ45 STP.png', 17, 3, 1),
('Conector RJ45 Crimpado', 'Conector RJ45 crimpado UTP', 2.50, 300, 'CABLADO/RJ45/Conector RJ45 Crimpado.png', 17, 3, 1),
('Conector RJ45 Gigabit', 'Conector RJ45 para red Gigabit', 3.00, 180, 'CABLADO/RJ45/Conector RJ45 Gigabit.png', 17, 3, 1),

-- Patch Panels (id_subcategoria = 18)
('Patch Panel 24 Puertos', 'Panel de conexión 24 puertos para cableado estructurado', 45.00, 25, 'CABLADO/PATCH/Patch Panel 24 Puertos.png', 18, 3, 1),
('Patch Panel 48 Puertos', 'Panel de 48 puertos para cableado estructurado', 80.00, 15, 'CABLADO/PATCH/Patch Panel 48 Puertos.png', 18, 3, 1),
('Patch Panel Blindado 24 Puertos', 'Patch panel blindado 24 puertos', 60.00, 20, 'CABLADO/PATCH/Patch Panel Blindado 24.png', 18, 3, 1),
('Patch Panel Modular 24 Puertos', 'Patch panel modular 24 puertos', 50.00, 30, 'CABLADO/PATCH/Patch Panel Modular 24.png', 18, 3, 1),
('Patch Panel 12 Puertos', 'Panel de 12 puertos sencillo', 25.00, 40, 'CABLADO/PATCH/Patch Panel 12 Puertos.png', 18, 3, 1),

-- Cerraduras Inalámbricas (id_subcategoria = 23)
('Cerradura Inteligente WiFi', 'Cerradura inalámbrica con control remoto y app', 280.00, 20, 'CONTROL DE ACCESO/CERRADURAS/Cerradura WiFi.png', 23, 1, 1),
('Cerradura Biométrica Huella', 'Cerradura con lector de huella integrada', 320.00, 15, 'CONTROL DE ACCESO/CERRADURAS/Cerradura Biométrica.png', 23, 1, 1),
('Cerradura RFID 125kHz', 'Cerradura compatible con tags RFID', 150.00, 30, 'CONTROL DE ACCESO/CERRADURAS/Cerradura RFID.png', 23, 3, 1),
('Cerradura Electrónica Digital', 'Cerradura electrónica con teclado numérico', 200.00, 25, 'CONTROL DE ACCESO/CERRADURAS/Cerradura Digital.png', 23, 3, 1),
('Cerradura Smart Deadbolt', 'Cerradura Smart Deadbolt con bluetooth', 350.00, 10, 'CONTROL DE ACCESO/CERRADURAS/Deadbolt Smart.png', 23, 1, 1),

-- Intercomunicadores (id_subcategoria = 22)
('Intercomunicador IP 7" Touch', 'Monitor de 7 pulgadas para intercomunicador IP', 180.00, 25, 'CONTROL DE ACCESO/INTERCOMUNICADORES/Intercom 7\".png', 22, 2, 6),
('Intercomunicador Video 4.3\"', 'Intercomunicador video 4.3 pulgadas', 120.00, 30, 'CONTROL DE ACCESO/INTERCOMUNICADORES/Intercom 4.3\".png', 22, 2, 7),
('Intercomunicador Audio', 'Intercomunicador solo audio para control de acceso', 80.00, 40, 'CONTROL DE ACCESO/INTERCOMUNICADORES/Intercom Audio.png', 22, 2, 8),
('Kit Intercom 2 Puertas', 'Kit intercomunicador 2 puertas con fuente incluida', 300.00, 15, 'CONTROL DE ACCESO/INTERCOMUNICADORES/Intercom Kit 2p.png', 22, 2, 7),
('Intercom Portero Ip', 'Portero IP residencial', 220.00, 20, 'CONTROL DE ACCESO/INTERCOMUNICADORES/Portero IP.png', 22, 2, 9),

-- Lector de Huella Digital (id_subcategoria = 24)
('Lector Huella Digital Biométrico', 'Lector de huella para control de accesos', 120.00, 50, 'CONTROL DE ACCESO/LECTORES/Lector Huella.png', 24, 1, 1),
('Lector Huella + RFID', 'Lector combinado huella + tarjeta', 160.00, 40, 'CONTROL DE ACCESO/LECTORES/Lector Huella RFID.png', 24, 1, 1),
('Lector de Tarjeta 125kHz', 'Lector de proximidad 125kHz', 80.00, 60, 'CONTROL DE ACCESO/LECTORES/Lector Tarjeta 125kHz.png', 24, 3, 1),
('Control Acceso Biometrico Standalone', 'Control biométrico standalone 1 puerta', 200.00, 25, 'CONTROL DE ACCESO/LECTORES/Standalone Biometrico.png', 24, 1, 1),
('Lector Huella WiFi', 'Lector de huella con conexión WiFi', 250.00, 15, 'CONTROL DE ACCESO/LECTORES/Lector Huella WiFi.png', 24, 1, 1),

-- Módulos de Control de Acceso (id_subcategoria = 25)
('Controlador 2 Puertas', 'Módulo controlador para 2 puertas', 180.00, 25, 'CONTROL DE ACCESO/MODULOS/Controlador 2 Puertas.png', 25, 1, 1),
('Controlador 4 Puertas', 'Módulo controlador para 4 puertas', 300.00, 15, 'CONTROL DE ACCESO/MODULOS/Controlador 4 Puertas.png', 25, 1, 1),
('Controlador para 1 Puerta', 'Módulo controlador sencillo 1 puerta', 120.00, 30, 'CONTROL DE ACCESO/MODULOS/Controlador 1 Puerta.png', 25, 1, 1),
('Controlador TCP/IP 2 Puertas', 'Controlador con conexión TCP/IP 2 puertas', 220.00, 20, 'CONTROL DE ACCESO/MODULOS/Controlador TCP2P.png', 25, 1, 1),
('Controlador Pro 4 Puertas', 'Controlador profesional para 4 puertas', 400.00, 10, 'CONTROL DE ACCESO/MODULOS/Controlador Pro 4P.png', 25, 1, 1),

-- Tags de Proximidad (id_subcategoria = 21)
('Tag Proximidad 125kHz x1', 'Tarjeta de proximidad 125kHz', 3.50, 500, 'CONTROL DE ACCESO/TAGS/Tag 125kHz.png', 21, 1, 1),
('Tag Proximidad 125kHz x10', 'Paquete 10 tags 125kHz', 30.00, 300, 'CONTROL DE ACCESO/TAGS/Pack 10 Tags.png', 21, 1, 1),
('Tag Proximidad 125kHz x50', 'Paquete 50 tags 125kHz', 140.00, 200, 'CONTROL DE ACCESO/TAGS/Pack 50 Tags.png', 21, 1, 1),
('Tag Proximidad 13.56MHz x1', 'Tag proximidad 13.56MHz', 5.00, 400, 'CONTROL DE ACCESO/TAGS/Tag 13MHz.png', 21, 1, 1),
('Tag Proximidad 13.56MHz x10', 'Paquete 10 tags 13.56MHz', 45.00, 250, 'CONTROL DE ACCESO/TAGS/Pack 10 Tags.png', 21, 1, 1),

-- Alarmas (VIDEOVIGILANCIA, id_subcategoria = 5)
('Alarma Sensor Movimiento', 'Alarma PIR con sensor de movimiento', 50.00, 120, 'VIDEOVIGILANCIA/Alarmas/Alarma PIR.png', 5, 1, 1),
('Alarma Sirena 110dB', 'Sirena de 110dB para sistemas de alarma', 35.00, 150, 'VIDEOVIGILANCIA/Alarmas/Sirena 110dB.png', 5, 1, 1),
('Kit Alarma Hogar 4 Sensores', 'Kit alarma hogar con 4 sensores y sirena', 120.00, 80, 'VIDEOVIGILANCIA/Alarmas/Kit 4 Sensores.png', 5, 1, 1),
('Alarma Magnética Puerta/Ventana', 'Alarma magnética para puertas o ventanas', 25.00, 200, 'VIDEOVIGILANCIA/Alarmas/Alarma Magnética.png', 5, 1, 1),
('Alarma Sensor Humo', 'Sensor de humo con alarma integrada', 60.00, 100, 'VIDEOVIGILANCIA/Alarmas/Alarma Humo.png', 5, 1, 1);
