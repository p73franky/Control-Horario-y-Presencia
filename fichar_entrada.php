<?php
include "db.php";


$id_empleado = $_SESSION['empleado']['id'];
$hoy = date("Y-m-d");
$hora_actual = date("H:i:s");

/* =========================
   LÍMITE DE FICHAJES POR DÍA
   ========================= */
$consulta = $conn->query("
    SELECT COUNT(*) AS total
    FROM fichajes
    WHERE empleado_id = $id_empleado
    AND fecha = '$hoy'
");

$total = $consulta->fetch_assoc()['total'];

if ($total >= 20) {
    die("❌ Has alcanzado el límite de 20 fichajes diarios.");
}

/* =========================
   INSERTAR NUEVO FICHAJE
   ========================= */
$conn->query("
    INSERT INTO fichajes (empleado_id, fecha, hora_entrada)
    VALUES ($id_empleado, '$hoy', '$hora_actual')
");

echo "✅ Fichaje registrado correctamente";
