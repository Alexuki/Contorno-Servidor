<?php
require_once "lib/base_datos.php";

$cookieNameNumVisitas = "nVisitasUsuarios";
$cookieNameIdioma = "idioma";

$cookieNumVisitas = $_COOKIE[$cookieNameNumVisitas] ?? null;
$cookieIdioma = $_COOKIE[$cookieNameIdioma] ?? null;

$cookieNumVisitasValue = isset($cookieNumVisitas) ? $cookieNumVisitas : 0;
$cookieIdiomaValue = isset($cookieIdioma) ? $cookieIdioma : "galego";

$cookieNumVisitasValue++;

setcookie($cookieNameNumVisitas, $cookieNumVisitasValue, time() + 86400 * 30, "/");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tienda IES San Clemente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <h1>Tienda IES San Clemente</h1>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous">
    </script>

    <div class="container justify-content-between">
        <h2>
            <?php
                switch ($cookieIdiomaValue) {
                    case 'castellano':
                        echo 'Bienvenido a mi página web.';
                        break;
                    case 'english':
                        echo 'Welcome to my webpage.';
                        break;
                    case 'galego':
                    default:
                        echo 'Benvido á miña páxina web.';
                }
            ?>
        </h2>
    </div>

    <?php
    /* Al conectar a esta página debe crear la conexión al servidor de BBDD,
        crear la base de datos y la tabla de usuarios. */
    $bd_nombre = "tienda";
    $conexion = get_conexion();
    crear_bd($conexion, $bd_nombre);
    seleccionar_bd($conexion, $bd_nombre);
    crear_tabla_usuarios($conexion);
    crear_tabla_productos($conexion);
    cerrar_conexion($conexion);
    ?>

    <p>
        <a class="btn btn-primary" href="dar_de_alta.php" role="button"> Alta usuarios</a>
        <a class="btn btn-primary" href="listar.php" role="button"> Listar usuarios</a>
        <a class="btn btn-primary" href="alta_producto.php" role="button"> Alta productos</a>
        <a class="btn btn-primary" href="idioma.php" role="button"> Seleccionar idioma</a>
    </p>

    <footer>
        <p>
            <a href='index.php'>Página de inicio</a>
        </p>
    </footer>

</body>

</html>