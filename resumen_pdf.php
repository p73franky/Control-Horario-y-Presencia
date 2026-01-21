<?php
require('fpdf186/fpdf.php');
include "db.php";

$id = $_SESSION['empleado']['id'];
$mes = date("m");
$anio = date("Y");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,'Resumen mensual',1,1);

$res = $conn->query("SELECT * FROM fichajes 
    WHERE empleado_id=$id AND MONTH(fecha)=$mes");

while($f = $res->fetch_assoc()){
    $pdf->Cell(0,8,$f['fecha']." Entrada: ".$f['hora_entrada']." Salida: ".$f['hora_salida'],1,1);
}

$pdf->Output();
