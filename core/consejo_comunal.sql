-- Base de datos: sistema_gestion_brasil
CREATE DATABASE IF NOT EXISTS sistema_gestion_brasil;
USE sistema_gestion_brasil;

-- Tabla de habitantes
CREATE TABLE habitante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(12) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de usuarios
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    habitante_id INT NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    estado TINYINT DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (habitante_id) REFERENCES habitante(id)
);

-- Tabla de pagos
CREATE TABLE pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    descripcion TEXT,
    fecha_pago DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- Tabla de comunicados
CREATE TABLE comunicado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    fecha_publicacion DATE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de eventos
CREATE TABLE evento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    fecha DATE,
    lugar VARCHAR(255),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de comentarios
CREATE TABLE comentario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    tipo_referencia ENUM('evento', 'comunicado') NOT NULL,
    referencia_id INT NOT NULL,
    contenido TEXT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

-- Tabla de notificaciones
CREATE TABLE notificacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    mensaje TEXT NOT NULL,
    leido TINYINT DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);
