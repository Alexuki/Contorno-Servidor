<?php
    // Inicio sesión. Los datos se guardan en $_SESSION
    session_start();

    //NOTA:
    //$x++  →  devuelve el valor ACTUAL, y DESPUÉS incrementa

    //$_SESSION["count"] = isset($_SESSION["count"]) ? $_SESSION["count"] + 1 : 0;
    
    isset($_SESSION["count"]) ? $_SESSION["count"]++ : $_SESSION["count"] = 0;

    $_SESSION["color"] = "rojo";
    $_SESSION["fruta"] = "manzana";
?>

<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>1. Sesiones</title>
    </head>
    <body>
        <h1>Sesión iniciada</h1>
        <p>Contador de recargas: <?= $_SESSION["count"] ?></p>
        <p>Color asignado: <?= $_SESSION["color"] ?></p>
        <p>Fruta asignada: <?= $_SESSION["fruta"] ?></p>

        <a href="1b.otraPagina.php">Ir a otra página en la misma sesión</a>
        <a href="1c.cookies.php">Gestión de cookies</a>
        
    </body>
</html>
