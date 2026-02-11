<?php
include "db.php";
if (!isset($_SESSION['empleado'])) header("Location: login.php");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/estilo.css">
    <script src="assets/js/reloj.js"></script>
</head>
<body>

<h1 id="reloj"></h1>

<div class="botones">
    <a href="fichar_entrada.php">Fichar Entrada</a>
    <a href="pausa_inicio.php">Iniciar Pausa</a>
    <a href="pausa_fin.php">Finalizar Pausa</a>
    <a href="fichar_salida.php">Fichar Salida</a>
</div>

<br>
<a href="ausencias.php">Justificar Ausencia</a> |
<a href="resumen_pdf.php">Resumen Mensual PDF</a> |
<a href="logout.php">Salir</a>

</body>
</html>
