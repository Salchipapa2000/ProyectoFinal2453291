sql
CREATE DATABASE IF NOT EXISTS manos_de_chichi
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE manos_de_chichi;

-- Tabla de artesanos
CREATE TABLE IF NOT EXISTS artesanos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    comunidad VARCHAR(100) NOT NULL,
    especialidad VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NULL
) ENGINE=InnoDB;

-- Tabla principal: productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artesano_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    existencia INT NOT NULL DEFAULT 0,
    existencia_minima INT NOT NULL DEFAULT 0,

    CONSTRAINT chk_producto_precio
        CHECK (precio > 0),

    CONSTRAINT chk_producto_existencia
        CHECK (existencia >= 0),

    CONSTRAINT chk_producto_existencia_minima
        CHECK (existencia_minima >= 0),

    CONSTRAINT fk_producto_artesano
        FOREIGN KEY (artesano_id)
        REFERENCES artesanos(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Tabla de usuarios del sistema
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'vendedor') NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_usuario_estado
        CHECK (estado IN (0, 1))
) ENGINE=InnoDB;

-- Tabla de ventas
CREATE TABLE IF NOT EXISTS ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_venta_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Tabla detalle de ventas
CREATE TABLE IF NOT EXISTS detalle_ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    venta_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_detalle_venta
        FOREIGN KEY (venta_id)
        REFERENCES ventas(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_detalle_producto
        FOREIGN KEY (producto_id)
        REFERENCES productos(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Datos de prueba para comenzar a trabajar
INSERT INTO artesanos (nombre, comunidad, especialidad, telefono)
VALUES
('María López', 'Chichicastenango', 'Textiles', '5555-1234'),
('Pedro Ajanel', 'Chichicastenango', 'Máscaras', '5555-5678'),
('Ana Pérez', 'Santa Cruz del Quiché', 'Cerámica', '5555-9012');

