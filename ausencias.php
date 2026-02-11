<?php
include "db.php";
$id = $_SESSION['empleado']['id'];

if ($_POST) {
    $fecha = $_POST['fecha'];
    $motivo = $_POST['motivo'];
    $conn->query("INSERT INTO ausencias (empleado_id,fecha,motivo)
                  VALUES ($id,'$fecha','$motivo')");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ausencias</title>
    <link rel="stylesheet" href="assets/css/estilo.css">
</head>
<body>
    <form method="post">
    <h2>Justificar ausencia</h2>
    <input type="date" name="fecha" required><br>
    <textarea name="motivo" required></textarea><br>
    <button>Guardar</button>
</form>
<a href="index.php">Volver</a>
</body>
</html>


