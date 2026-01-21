<?php
include "db.php";
require_once __DIR__ . "/fpdf/fpdf.php";

$id   = $_SESSION['empleado']['id'];
$mes  = date("m");
$anio = date("Y");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,"Resumen mensual $mes/$anio",0,1,'C');
$pdf->Ln(4);

/* =========================
   FICHAJES + PAUSAS
   ========================= */
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"Fichajes y pausas",0,1);

$pdf->SetFont('Arial','',10);

$fichajes = $conn->query("
    SELECT * FROM fichajes
    WHERE empleado_id = $id
    AND MONTH(fecha) = $mes
    AND YEAR(fecha) = $anio
    ORDER BY fecha
");

if ($fichajes->num_rows == 0) {
    $pdf->Cell(0,8,"No hay fichajes este mes",1,1);
}

while ($f = $fichajes->fetch_assoc()) {

    $pdf->Cell(
        0,
        8,
        "Fecha: {$f['fecha']} | Entrada: {$f['hora_entrada']} | Salida: {$f['hora_salida']}",
        1,
        1
    );

    // Pausas del día
    $pausas = $conn->query("
        SELECT hora_inicio, hora_fin
        FROM pausas
        WHERE fichaje_id = {$f['id']}
    ");

    if ($pausas->num_rows > 0) {
        while ($p = $pausas->fetch_assoc()) {
            $pdf->Cell(
                0,
                7,
                "   Pausa: {$p['hora_inicio']} - {$p['hora_fin']}",
                1,
                1
            );
        }
    } else {
        $pdf->Cell(0,7,"   Sin pausas",1,1);
    }

    $pdf->Ln(1);
}

/* =========================
   AUSENCIAS
   ========================= */
$pdf->Ln(3);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"Ausencias justificadas",0,1);

$pdf->SetFont('Arial','',10);

$ausencias = $conn->query("
    SELECT fecha, motivo
    FROM ausencias
    WHERE empleado_id = $id
    AND MONTH(fecha) = $mes
    AND YEAR(fecha) = $anio
    ORDER BY fecha
");

if ($ausencias->num_rows == 0) {
    $pdf->Cell(0,8,"No hay ausencias este mes",1,1);
} else {
    while ($a = $ausencias->fetch_assoc()) {
        $pdf->MultiCell(
            0,
            8,
            "Fecha: {$a['fecha']} | Motivo: {$a['motivo']}",
            1
        );
    }
}

$pdf->Output("I", "resumen_$mes-$anio.pdf");
