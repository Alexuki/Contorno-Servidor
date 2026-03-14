<?php
session_start();

function comprobar_usuario($nombre, $pass)
{
    if ($nombre == "usuario" && $pass == "abc123.") {
        $usuario['nombre'] = "usuario";
        $usuario['rol'] = 0;
        return $usuario;
    } elseif ($nombre == "admin" && $pass == "1234") {
        $usuario['nombre'] = "admin";
        $usuario['rol'] = 1;
        return $usuario;
    } else {
        return false;
    }
}

//Comprobar si se reciben los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $pass = $_POST["pass"];
    $user = comprobar_usuario($usuario, $pass);
    if (!$user) {
        $error = true;
    } else {
        $_SESSION['usuario'] = $user;
        //Redirigimos a index.php
        header('Location: index.php');
    }
}
?>

<html>

<body>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        Usuario: <input name="usuario" id="usuario" type="text">
        Contraseña: <input name="pass" id="pass" type="password">
        <input type="submit">
    </form>
</body>

</html>