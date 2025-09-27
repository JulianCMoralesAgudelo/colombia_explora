-- ==========================================
-- Schema para "Viajes Colombia" - InfinityFree
-- ==========================================

-- -----------------------------------------------------
-- Tabla Roles
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar roles por defecto
INSERT INTO roles (nombre_rol) VALUES ('admin'), ('cliente');

-- -----------------------------------------------------
-- Tabla Usuarios
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- para hash seguro
    rol INT NOT NULL,
    FOREIGN KEY (rol) REFERENCES roles(id_rol) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla Destinos
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS destinos (
    id_destino INT AUTO_INCREMENT PRIMARY KEY,
    ciudad VARCHAR(100) NOT NULL,
    hotel VARCHAR(100) NOT NULL,
    costo DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar destinos de ejemplo
INSERT INTO destinos (ciudad, hotel, costo) VALUES
('Cartagena','Hotel Caribe',200.00),
('Medellin','Hotel Plaza',150.00),
('Bogota','Hotel Real',180.00);

-- -----------------------------------------------------
-- Tabla Reservaciones
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS reservaciones (
    id_reservacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_destino INT NOT NULL,
    fecha_reserva DATE NOT NULL,
    numero_personas INT NOT NULL,
    costo_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_destino) REFERENCES destinos(id_destino) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Insertar usuario admin de ejemplo
-- -----------------------------------------------------
-- Nota: la contraseña se guarda en hash (ejemplo: hash de "admin123")
INSERT INTO usuarios (nombre, correo, password, rol) VALUES
('Administrador', 'admin@viajescolombia.com', 
'$2y$10$uJXw6vYp3dI1G6Qhr3w9COx1ZTPQjR5yG5R9f5c7nI6fZQH9yFq6e', 1);