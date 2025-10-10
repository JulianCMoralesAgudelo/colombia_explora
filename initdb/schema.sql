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
-- Tabla password_resets
-- -----------------------------------------------------
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    INDEX (email),
    INDEX (token)
);
-- -----------------------------------------------------
-- Insertar usuario admin de ejemplo
-- -----------------------------------------------------
-- Nota: la contraseña se guarda en hash (ejemplo: hash de "admin123")
INSERT INTO usuarios (nombre, correo, password, rol) VALUES
('Administrador', 'admin@viajescolombia.com', 
'$2y$10$uJXw6vYp3dI1G6Qhr3w9COx1ZTPQjR5yG5R9f5c7nI6fZQH9yFq6e', 1);

