-- ================================
-- SISTEMA DE GESTIÓN COMUNAL
-- PostgreSQL - Script Completo
-- Con soft delete y auditoría
-- ================================

-- ===========================================
-- FUNCIONES Y TRIGGERS GLOBALES DE AUDITORÍA
-- ===========================================

-- Función para actualizar el campo 'fecha_actualizacion'
CREATE OR REPLACE FUNCTION actualizar_fecha_actualizacion()
RETURNS TRIGGER AS $$
BEGIN
  NEW.fecha_actualizacion = CURRENT_TIMESTAMP;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- ===========================================
-- TABLA PERSONA
-- ===========================================
CREATE TABLE persona (
    id_persona SERIAL PRIMARY KEY,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    sexo VARCHAR(10),
    telefono VARCHAR(20),
    direccion TEXT,
    correo VARCHAR(100),
    estado VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_persona
BEFORE UPDATE ON persona
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA HABITANTE
-- ===========================================
CREATE TABLE habitante (
    id_habitante SERIAL PRIMARY KEY,
    id_persona INT REFERENCES persona(id_persona),
    fecha_ingreso DATE,
    condicion VARCHAR(50),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_habitante
BEFORE UPDATE ON habitante
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA VIVIENDA
-- ===========================================
CREATE TABLE vivienda (
    id_vivienda SERIAL PRIMARY KEY,
    direccion VARCHAR(255),
    numero VARCHAR(20),
    tipo VARCHAR(50),
    sector VARCHAR(100),
    estado VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_vivienda
BEFORE UPDATE ON vivienda
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA HABITANTE_VIVIENDA
-- ===========================================
CREATE TABLE habitante_vivienda (
    id_habitante INT REFERENCES habitante(id_habitante),
    id_vivienda INT REFERENCES vivienda(id_vivienda),
    es_jefe_familia BOOLEAN,
    fecha_inicio DATE,
    fecha_salida DATE,
    PRIMARY KEY (id_habitante, id_vivienda)
);

-- ===========================================
-- TABLA ROL
-- ===========================================
CREATE TABLE rol (
    id_rol SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_rol
BEFORE UPDATE ON rol
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA PERMISO
-- ===========================================
CREATE TABLE permiso (
    id_permiso SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_permiso
BEFORE UPDATE ON permiso
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA ROL_PERMISO
-- ===========================================
CREATE TABLE rol_permiso (
    id_rol INT REFERENCES rol(id_rol),
    id_permiso INT REFERENCES permiso(id_permiso),
    PRIMARY KEY (id_rol, id_permiso)
);

-- ===========================================
-- TABLA USUARIO
-- ===========================================
CREATE TABLE usuario (
    id_usuario SERIAL PRIMARY KEY,
    id_habitante INT REFERENCES habitante(id_habitante),
    id_rol INT REFERENCES rol(id_rol),
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_usuario
BEFORE UPDATE ON usuario
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- Las siguientes tablas se agregarán en un segundo bloque por tamaño.

-- ===========================================
-- TABLA LIDER_CALLE
-- ===========================================
CREATE TABLE lider_calle (
    id_habitante INT PRIMARY KEY REFERENCES habitante(id_habitante),
    sector VARCHAR(100) NOT NULL,
    zona VARCHAR(100),
    fecha_designacion DATE,
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_lider_calle
BEFORE UPDATE ON lider_calle
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA LIDER_COMUNAL
-- ===========================================
CREATE TABLE lider_comunal (
    id_habitante INT PRIMARY KEY REFERENCES habitante(id_habitante),
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    observaciones TEXT,
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_lider_comunal
BEFORE UPDATE ON lider_comunal
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA CARGA_FAMILIAR
-- ===========================================
CREATE TABLE carga_familiar (
    id_carga SERIAL PRIMARY KEY,
    id_habitante INT REFERENCES habitante(id_habitante),
    id_jefe INT REFERENCES habitante(id_habitante),
    parentesco VARCHAR(50),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_carga_familiar
BEFORE UPDATE ON carga_familiar
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA CONCEPTO_PAGO
-- ===========================================
CREATE TABLE concepto_pago (
    id_concepto SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_concepto_pago
BEFORE UPDATE ON concepto_pago
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA PAGO
-- ===========================================
CREATE TABLE pago (
    id_pago SERIAL PRIMARY KEY,
    id_usuario INT REFERENCES usuario(id_usuario),
    id_concepto INT REFERENCES concepto_pago(id_concepto),
    monto DECIMAL(10,2),
    fecha_pago DATE,
    estado_pago VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_pago
BEFORE UPDATE ON pago
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA COMUNICADO
-- ===========================================
CREATE TABLE comunicado (
    id_comunicado SERIAL PRIMARY KEY,
    id_usuario INT REFERENCES usuario(id_usuario),
    titulo VARCHAR(100),
    contenido TEXT,
    fecha_publicacion DATE,
    estado VARCHAR(20),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_comunicado
BEFORE UPDATE ON comunicado
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA COMENTARIO
-- ===========================================
CREATE TABLE comentario (
    id_comentario SERIAL PRIMARY KEY,
    id_comunicado INT REFERENCES comunicado(id_comunicado),
    id_usuario INT REFERENCES usuario(id_usuario),
    contenido TEXT,
    fecha_comentario DATE,
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_comentario
BEFORE UPDATE ON comentario
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA EVENTO
-- ===========================================
CREATE TABLE evento (
    id_evento SERIAL PRIMARY KEY,
    titulo VARCHAR(100),
    descripcion TEXT,
    fecha_evento DATE,
    lugar VARCHAR(100),
    creado_por INT REFERENCES usuario(id_usuario),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_evento
BEFORE UPDATE ON evento
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA PARTICIPACION_EVENTO
-- ===========================================
CREATE TABLE participacion_evento (
    id_participacion SERIAL PRIMARY KEY,
    id_evento INT REFERENCES evento(id_evento),
    id_usuario INT REFERENCES usuario(id_usuario),
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_participacion
BEFORE UPDATE ON participacion_evento
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();

-- ===========================================
-- TABLA INDICADOR_GESTION
-- ===========================================
CREATE TABLE indicador_gestion (
    id_indicador SERIAL PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    valor DECIMAL(10,2),
    fecha_registro DATE,
    generado_por INT REFERENCES usuario(id_usuario),
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER trg_actualizar_fecha_indicador
BEFORE UPDATE ON indicador_gestion
FOR EACH ROW
EXECUTE FUNCTION actualizar_fecha_actualizacion();