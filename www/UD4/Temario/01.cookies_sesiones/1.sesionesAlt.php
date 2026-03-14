<?php
    session_start();
?>

<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>1. Sesiones (alternativa)</title>
    </head>
    <body>
        <h1>Sesión iniciada</h1>
        <p>Contador de recargas: <?= $_SESSION["count"] ?></p>
        <p>Color asignado: <?= $_SESSION["color"] ?></p>
        <p>Fruta asignada: <?= $_SESSION["fruta"] ?></p>    
    </body>
</html>
