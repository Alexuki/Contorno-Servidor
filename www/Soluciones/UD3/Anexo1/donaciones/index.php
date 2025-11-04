<?php

require_once "lib/base_datos.php";
require_once "lib/utilidades.php";

$conexion = get_conexion();

crear_bd($conexion,"donacion");
seleccionar_bd($conexion,"donacion");
crear_tabla_administradores($conexion);
crear_tabla_donantes($conexion);
crear_tabla_historico($conexion);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Donación Sangre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
    <br>
    <h1>Gestión donacion de Sangre</h1>
    <div>
        <a class="btn btn-primary" href="dar_alta_donante.php" role="button">Alta donantes</a>
        <a class="btn btn-primary" href="buscar_donantes.php" role="button">Buscar donantes</a>
        <a class="btn btn-primary" href="listar_donantes.php" role="button">Listar donantes</a>
        <a class="btn btn-primary" href="dar_alta_administrador.php" role="button">Nuevos administradores</a>
    </div>

    <footer>
        <p><a href='index.php'>Página de inicio</a></p>
    </footer>

    <?php cerrar_conexion($conexion);?>

</body>

</html>