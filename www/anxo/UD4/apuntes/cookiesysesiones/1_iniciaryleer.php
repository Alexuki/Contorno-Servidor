<?php
/* Iniciar sesión SIEMPRE antes de cualquier salida */
session_start();

/* Si no existe el contador, lo inicializamos */
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
} else {
    $_SESSION['count']++;
}

/* Guardamos otras 2 variables de sesión */
$_SESSION["favcolor"] = "verde";
$_SESSION["favanimal"] = "gato";
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>1. Iniciar y leer sesión</title></head>
<body>
    <h1>Sesión iniciada</h1>
    <p>Contador de recargas: <b><?= $_SESSION['count'] ?></b></p>
    <p>Color favorito (sesión): <b><?= $_SESSION['favcolor'] ?></b></p>
    <p>Animal favorito (sesión): <b><?= $_SESSION['favanimal'] ?></b></p>

    <p><a href="2_usarenotrapagina.php">Ir a otra página y seguir usando la sesión</a></p>
    <p><a href="4_cookiesbasico.php">Gestionar cookies</a></p>
</body>
</html>