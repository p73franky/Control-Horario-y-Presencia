<?php
include "db.php";
$id = $_SESSION['empleado']['id'];
$fecha = date("Y-m-d");

$conn->query("UPDATE fichajes SET hora_salida=CURTIME()
              WHERE empleado_id=$id AND fecha='$fecha'");

header("Location: index.php");
