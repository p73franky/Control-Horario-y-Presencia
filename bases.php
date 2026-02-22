-- ===============================
-- CREAR BASE DE DATOS
-- ===============================
CREATE DATABASE IF NOT EXISTS control_horario;
USE control_horario;

-- ===============================
-- TABLA EMPLEADOS
-- ===============================
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL
);

-- ===============================
-- TABLA FICHAJES
-- ===============================
CREATE TABLE fichajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    fecha DATE NOT NULL,
    hora_entrada TIME,
    hora_salida TIME,
    latitud DECIMAL(9,6),
    longitud DECIMAL(9,6),
    FOREIGN KEY (empleado_id) REFERENCES empleados(id)
);

-- ===============================
-- TABLA PAUSAS
-- ===============================
CREATE TABLE pausas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fichaje_id INT NOT NULL,
    hora_inicio TIME,
    hora_fin TIME,
    FOREIGN KEY (fichaje_id) REFERENCES fichajes(id)
);

-- ===============================
-- TABLA AUSENCIAS
-- ===============================
CREATE TABLE ausencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    fecha DATE NOT NULL,
    motivo TEXT NOT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id)
);

-- ===============================
-- DATOS DE PRUEBA
-- ===============================
INSERT INTO empleados (nombre, email, password)
VALUES ('Juan Pérez', 'juan@test.com', '1234');
