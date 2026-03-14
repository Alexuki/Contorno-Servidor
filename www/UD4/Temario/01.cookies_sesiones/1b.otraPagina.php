<?php
    // Inicio sesión. Imprescindible para acceder a los datos de $_SESSION
    session_start();
?>

<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>1b. Otra página en la misma sesión</title>
    </head>
    <body>
        <h1>Otra página con la sesión de 1.Sesiones</h1>
        <p>ID de la sesión: <?= session_id() ?></p>
        <p>Contador de recargas (recuperado): <?= $_SESSION["count"] ?? "Contador no definido" ?></p>
        <p>Color (recuperado): <?= htmlspecialchars($_SESSION["color"] ?? "Variable no definida") ?></p>
        <p>Fruta (recuperado): <?= htmlspecialchars($_SESSION["fruta"] ?? "Variable no definida") ?></p>

        <a href="1.sesiones.php">Volver a página principal</a>
        <a href="1d.gestionarSesion.php">Modificar o eliminar sesión</a>
        
    </body>
</html>
