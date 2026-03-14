<?php 
    session_start();
    if(!isset($_SESSION['usuario'])){
        header("Location: 1_login.php?redirigido=true");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Página principal</title>
        <meta charset="UTF-8">
    </head>

    <body>
        <?= "Bienvenido " . $_SESSION['usuario']['nombre']; ?>
        <br><a href="2_logout.php"> Salir <a>
    </body>
</html>