<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>App Fichaje</title>
</head>
<body>
    <form method="POST" action="control.php">
        <input type="text" name="nombre" placeholder="Usuario" required>

        <button name="accion" value="fichar">Fichar entrada / salida</button>
        <button name="accion" value="pausar">Pausar turno</button>
        <button name="accion" value="continuar">Continuar turno</button>
    </form>

</body>
</html>
