<?php
include "db.php";

$id    = $_SESSION['empleado']['id'];
$fecha = date("Y-m-d");

// Solo guardar salida si aún no existe
$conn->query("
    UPDATE fichajes 
    SET hora_salida = CURTIME()
    WHERE empleado_id = $id
    AND fecha = '$fecha'
    AND hora_salida IS NULL
");

header("Location: index.php");

