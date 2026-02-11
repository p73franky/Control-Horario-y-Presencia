<?php
include "db.php";
$id = $_SESSION['empleado']['id'];
$fecha = date("Y-m-d");

$fichaje = $conn->query("SELECT * FROM fichajes 
    WHERE empleado_id=$id AND fecha='$fecha'")->fetch_assoc();

$conn->query("INSERT INTO pausas (fichaje_id,hora_inicio)
              VALUES ({$fichaje['id']},CURTIME())");

header("Location: index.php");
