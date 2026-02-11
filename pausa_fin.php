<?php
include "db.php";

$pausa = $conn->query("
    SELECT id FROM pausas
    WHERE hora_fin IS NULL
    ORDER BY id DESC
    LIMIT 1
")->fetch_assoc();

if ($pausa) {
    $conn->query("
        UPDATE pausas
        SET hora_fin = CURTIME()
        WHERE id = {$pausa['id']}
    ");
}

header("Location: index.php");