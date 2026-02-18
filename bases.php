📌 Base de datos
CREATE DATABASE control_horario;
USE control_horario;


Tabla empleados
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

⏱️ Tabla fichajes
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


☕ Tabla pausas (múltiples al día)
CREATE TABLE pausas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fichaje_id INT NOT NULL,
    hora_inicio TIME,
    hora_fin TIME,
    FOREIGN KEY (fichaje_id) REFERENCES fichajes(id)
);

❌ Tabla ausencias
CREATE TABLE ausencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    fecha DATE NOT NULL,
    motivo TEXT NOT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id)
);

✔ Datos reales de prueba (SQL)
INSERT INTO empleados (nombre,email,password)

VALUES ('Juan Pérez','juan@test.com', MD5('1234'));
