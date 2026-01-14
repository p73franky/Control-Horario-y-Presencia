<?php
require 'db.php';
require 'Usuario.php';

function obtenerUsuario($nombre, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = ?");
    $stmt->execute([$nombre]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$accion = $_POST['accion'] ?? null;
$nombre = $_POST['nombre'] ?? null;

if (!$accion || !$nombre) {
    die("Datos incompletos");
}

$usuarioDB = obtenerUsuario($nombre, $pdo);

if (!$usuarioDB) {
    die("Usuario no encontrado");
}

$usuario = new Usuario($usuarioDB['id'], $usuarioDB['nombre']);

$hoy = date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT * FROM fichajes 
    WHERE usuario_id = ? AND fecha = ? AND fin IS NULL
");
$stmt->execute([$usuario->id, $hoy]);
$fichaje = $stmt->fetch(PDO::FETCH_ASSOC);

$usuario->trabajando = $fichaje ? true : false;
$usuario->pausado = $fichaje && $fichaje['pausado'];

switch ($accion) {

    case 'fichar':
        if (!$usuario->trabajando) {
            // FICHAR ENTRADA
            $stmt = $pdo->prepare("
                INSERT INTO fichajes (usuario_id, fecha, inicio)
                VALUES (?, ?, NOW())
            ");
            $stmt->execute([$usuario->id, $hoy]);
            echo "Entrada registrada";
        } else {
            // FICHAR SALIDA
            $stmt = $pdo->prepare("
                UPDATE fichajes 
                SET fin = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$fichaje['id']]);
            echo "Salida registrada";
        }
        break;

    case 'pausar':
        if (!$usuario->trabajando || $usuario->pausado) {
            die("No puedes pausar");
        }

        $stmt = $pdo->prepare("
            UPDATE fichajes 
            SET pausado = 1, inicio_pausa = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$fichaje['id']]);
        echo "Turno pausado";
        break;

    case 'continuar':
        if (!$usuario->trabajando || !$usuario->pausado) {
            die("No puedes continuar");
        }

        $stmt = $pdo->prepare("
            UPDATE fichajes
            SET pausado = 0,
                tiempo_pausado = tiempo_pausado + TIMESTAMPDIFF(SECOND, inicio_pausa, NOW()),
                inicio_pausa = NULL
            WHERE id = ?
        ");
        $stmt->execute([$fichaje['id']]);
        echo "Turno reanudado";
        break;
}
