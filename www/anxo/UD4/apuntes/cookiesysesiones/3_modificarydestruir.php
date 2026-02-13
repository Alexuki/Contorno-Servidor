<?php
session_start();

/* Cambiar una variable */
$_SESSION['favcolor'] = 'amarillo';

/* Borrar UNA variable */
unset($_SESSION['favanimal']);

/* Botón: destruir TODA la sesión */
if (isset($_GET['destruir'])) {
    session_unset();   // elimina variables
    session_destroy(); // destruye el fichero de sesión
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>3. Modificar / destruir sesión</title></head>
<body>
    <h1>Modificar variables de sesión</h1>
    <p>Color favorito (cambiado ahora): <b><?= $_SESSION['favcolor'] ?></b></p>
    <p>Animal favorito (unseteado): <b><?= $_SESSION['favanimal'] ?? 'no existe' ?></b></p>

    <p><a href="?destruir=1">Destruir sesión completa</a></p>
    <p><a href="1_iniciaryleer.php">Volver a empezar</a></p>
</body>
</html>