<?php
include "db.php";

if ($_POST) {
    $email = $_POST['email'];
    $pass = md5($_POST['password']);

    $sql = $conn->query("SELECT * FROM empleados WHERE email='$email' AND password='$pass'");
    if ($sql->num_rows == 1) {
        $_SESSION['empleado'] = $sql->fetch_assoc();
        header("Location: index.php");
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/estilo.css">
</head>
<body>
    <form method="post">
    <h2>Login</h2>
    <input name="email" placeholder="Email" required><br>
    <input name="password" type="password" placeholder="Password" required><br>
    <button>Entrar</button>
    <?= isset($error) ? $error : "" ?>
</form>
</body>
</html>

