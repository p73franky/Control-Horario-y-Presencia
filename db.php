<?php
$conn = new mysqli("localhost", "root", "", "control_horario");
if ($conn->connect_error) {
    die("Error de conexión");
}
session_start();
?>
