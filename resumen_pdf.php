<?php
include "db.php";
require_once __DIR__ . "/fpdf186/fpdf.php";

$id   = $_SESSION['empleado']['id'];
$mes  = date("m");
$anio = date("Y");

/* =========================
   FUNCIÓN TIEMPO
   ========================= */
function horaASegundos($hora) {
    if (!$hora) return 0;
    list($h, $m, $s) = explode(':', $hora);
    return ($h * 3600) + ($m * 60) + $s;
}

/* =========================
   PDF CABECERA
   ========================= */
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,"Resumen mensual $mes/$anio",0,1,'C');
$pdf->Ln(4);

/* =========================
   FICHAJES Y PAUSAS
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

/* =========================
   CÁLCULO DE HORAS
   ========================= */
$total_segundos = 0;

$fichajes = $conn->query("
    SELECT id, hora_entrada, hora_salida
    FROM fichajes
    WHERE empleado_id = $id
    AND MONTH(fecha) = $mes
    AND YEAR(fecha) = $anio
");

while ($f = $fichajes->fetch_assoc()) {

    if (!$f['hora_entrada'] || !$f['hora_salida']) {
        continue;
    }

    $entrada = horaASegundos($f['hora_entrada']);
    $salida  = horaASegundos($f['hora_salida']);

    if ($salida <= $entrada) {
        continue;
    }

    $segundos_dia = $salida - $entrada;

    $pausas = $conn->query("
        SELECT hora_inicio, hora_fin
        FROM pausas
        WHERE fichaje_id = {$f['id']}
        AND hora_inicio IS NOT NULL
        AND hora_fin IS NOT NULL
    ");

    while ($p = $pausas->fetch_assoc()) {

        $inicio = horaASegundos($p['hora_inicio']);
        $fin    = horaASegundos($p['hora_fin']);

        // Ajustar la pausa al rango real del fichaje
        $inicio_real = max($inicio, $entrada);
        $fin_real    = min($fin, $salida);

        if ($fin_real > $inicio_real) {
            $segundos_dia -= ($fin_real - $inicio_real);
        }
    }

    if ($segundos_dia > 0) {
        $total_segundos += $segundos_dia;
    }
}

/* =========================
   FORMATEO FINAL
   ========================= */
$horas    = floor($total_segundos / 3600);
$minutos  = floor(($total_segundos % 3600) / 60);
$segundos = $total_segundos % 60;

$tiempo_formateado = sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);

/* =========================
   MOSTRAR RESULTADO
   ========================= */
$empleado = $conn->query("
    SELECT nombre FROM empleados WHERE id = $id
")->fetch_assoc();

$pdf->Ln(6);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"Horas trabajadas",0,1);

$pdf->SetFont('Arial','',11);
$pdf->Cell(0,8,"Empleado: {$empleado['nombre']}",1,1);
$pdf->Cell(0,8,"Total trabajado: $tiempo_formateado",1,1);

$pdf->Output("I", "resumen_$mes-$anio.pdf");