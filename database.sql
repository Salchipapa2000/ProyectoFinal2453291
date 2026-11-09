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

-- Datos de prueba para comenzar a trabajar
INSERT INTO artesanos (nombre, comunidad, especialidad, telefono)
VALUES
('María López', 'Chichicastenango', 'Textiles', '5555-1234'),
('Pedro Ajanel', 'Chichicastenango', 'Máscaras', '5555-5678'),
('Ana Pérez', 'Santa Cruz del Quiché', 'Cerámica', '5555-9012');

