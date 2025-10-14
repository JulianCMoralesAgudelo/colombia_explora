-- =====================================================
-- BASE DE DATOS: VIAJES COLOMBIA
-- =====================================================
CREATE DATABASE IF NOT EXISTS viajes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE viajes;

-- =====================================================
-- TABLA: ROLES
-- =====================================================
CREATE TABLE IF NOT EXISTS roles (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre_rol VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: USUARIOS
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  id_rol INT DEFAULT 2,
  FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: DESTINOS
-- =====================================================
CREATE TABLE IF NOT EXISTS destinos (
  id_destino INT AUTO_INCREMENT PRIMARY KEY,
  ciudad VARCHAR(100) NOT NULL,
  hotel VARCHAR(100) NOT NULL,
  costo DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: RESERVACIONES
-- =====================================================
CREATE TABLE IF NOT EXISTS reservaciones (
  id_reservacion INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_destino INT NOT NULL,
  fecha_reserva DATE NOT NULL,
  numero_personas INT NOT NULL,
  costo_total DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_destino) REFERENCES destinos(id_destino)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- INSERTAR DATOS BASE
-- =====================================================
INSERT INTO roles (nombre_rol) VALUES 
('admin'), 
('usuario');

INSERT INTO destinos (ciudad, hotel, costo) VALUES
('Medellin', 'Hotel Poblado Plaza', 350000.00),
('Bogota', 'Hotel Tequendama', 400000.00),
('Cartagena', 'Hotel Caribe', 550000.00);

-- =====================================================
-- TABLAS DE AUTENTICACIÓN / CONTACTO
-- =====================================================

-- Contactos / mensajes enviados desde formulario
CREATE TABLE IF NOT EXISTS mensajes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre    VARCHAR(100)  NOT NULL,
  correo    VARCHAR(120)  NOT NULL,
  telefono  VARCHAR(30)   NULL,
  categoria VARCHAR(40)   NULL,
  mensaje   TEXT          NOT NULL,
  fecha     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuarios del módulo auth (se puede vincular con la tabla principal)
CREATE TABLE IF NOT EXISTS usuarios_auth (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol VARCHAR(30) DEFAULT 'usuario',
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla para reset de contraseñas
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (email),
    INDEX (token)
);