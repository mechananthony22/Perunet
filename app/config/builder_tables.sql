-- Script para crear las tablas del Builder en la base de datos perunet
USE perunet;

-- 20. BUILDER CATEGORY (Categorías del constructor de PC y Setup)
CREATE TABLE IF NOT EXISTS builder_category (
  id_cat INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  tipo ENUM('pc', 'setup') NOT NULL,
  descripcion TEXT,
  orden INT DEFAULT 0,
  requerido BOOLEAN DEFAULT FALSE,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 21. BUILDER PRODUCT CATEGORY (Relación entre productos y categorías del builder)
CREATE TABLE IF NOT EXISTS builder_product_category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_producto INT NOT NULL,
  id_builder_category INT NOT NULL,
  FOREIGN KEY (id_producto) REFERENCES producto(id_pro) ON DELETE CASCADE,
  FOREIGN KEY (id_builder_category) REFERENCES builder_category(id_cat) ON DELETE CASCADE,
  UNIQUE KEY uk_product_builder_category (id_producto, id_builder_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 22. BUILDER CONFIGURATION (Configuraciones guardadas por usuarios)
CREATE TABLE IF NOT EXISTS builder_configuration (
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

-- BUILDER CATEGORIES
-- Categorías para PC Builder
INSERT IGNORE INTO builder_category (nombre, tipo, descripcion, orden, requerido) VALUES
('Procesador', 'pc', 'Selecciona el procesador para tu PC', 1, TRUE),
('Placa Base', 'pc', 'Selecciona la placa base compatible', 2, TRUE),
('Memoria RAM', 'pc', 'Selecciona la memoria RAM', 3, TRUE),
('Almacenamiento', 'pc', 'Selecciona discos SSD o HDD', 4, TRUE),
('Tarjeta Gráfica', 'pc', 'Selecciona la tarjeta gráfica (GPU)', 5, FALSE),
('Fuente de Poder', 'pc', 'Selecciona la fuente de alimentación', 6, TRUE),
('Gabinete', 'pc', 'Selecciona el gabinete para tu PC', 7, TRUE),
('Refrigeración', 'pc', 'Selecciona el sistema de refrigeración', 8, FALSE);

-- Categorías para Setup Builder
INSERT IGNORE INTO builder_category (nombre, tipo, descripcion, orden, requerido) VALUES
('Escritorio', 'setup', 'Selecciona tu escritorio', 1, TRUE),
('Silla', 'setup', 'Selecciona tu silla gamer', 2, TRUE),
('Monitor', 'setup', 'Selecciona tu monitor', 3, TRUE),
('Teclado', 'setup', 'Selecciona tu teclado', 4, TRUE),
('Mouse', 'setup', 'Selecciona tu mouse', 5, TRUE),
('Audífonos', 'setup', 'Selecciona tus audífonos', 6, FALSE),
('Micrófono', 'setup', 'Selecciona tu micrófono', 7, FALSE),
('Webcam', 'setup', 'Selecciona tu cámara web', 8, FALSE),
('Iluminación', 'setup', 'Selecciona tu iluminación LED', 9, FALSE);
