<?php
$host = "localhost";      // MySQL local en EC2
$usuario = "usuario";     // El que creaste en MySQL
$password = "password";   // Tu password de MySQL
$dbname = "control_horario";

try {
    // Conexión directa a la BD ya creada
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conectado correctamente<br>";

    // Tabla empleados
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS empleados (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )
    ");
    echo "Tabla empleados creada<br>";

    // Tabla fichajes
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS fichajes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            empleado_id INT NOT NULL,
            fecha DATE NOT NULL,
            hora_entrada TIME,
            hora_salida TIME,
            latitud DECIMAL(9,6),
            longitud DECIMAL(9,6),
            FOREIGN KEY (empleado_id) REFERENCES empleados(id)
                ON DELETE CASCADE
        )
    ");
    echo "Tabla fichajes creada<br>";

    // Tabla pausas
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS pausas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fichaje_id INT NOT NULL,
            hora_inicio TIME,
            hora_fin TIME,
            FOREIGN KEY (fichaje_id) REFERENCES fichajes(id)
                ON DELETE CASCADE
        )
    ");
    echo "Tabla pausas creada<br>";

    // Tabla ausencias
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS ausencias (
            id INT AUTO_INCREMENT PRIMARY KEY,
            empleado_id INT NOT NULL,
            fecha DATE NOT NULL,
            motivo TEXT NOT NULL,
            FOREIGN KEY (empleado_id) REFERENCES empleados(id)
                ON DELETE CASCADE
        )
    ");
    echo "Tabla ausencias creada<br>";

    // Insertar empleado de prueba con password_hash
    $passwordHash = password_hash('1234', PASSWORD_DEFAULT);
    $stmt = $conexion->prepare("
        INSERT IGNORE INTO empleados (nombre, email, password)
        VALUES ('Juan Pérez', 'juan@test.com', ?)
    ");
    $stmt->execute([$passwordHash]);
    echo "Usuario de prueba insertado<br>";

    echo "<h2>✅ Instalación completada correctamente</h2>";

} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>
