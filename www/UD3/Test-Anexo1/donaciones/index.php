<html lang="en">

    <?php
        require_once("lib/bbdd.php");
        $dbName = "donacionTest";
        $con = crearConexion();

        crearDB($con, $dbName);
        seleccionarDB($con, $dbName);
        crearTablaDonantes($con);
        crearTablaHistorico($con);
        crearTablaAdministradores($con);
        cerrarConexion($con);
    ?>

    <head>
        <meta charset="utf-8">
        <title>Donación Sangre</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>

    <body>
 
        <h1>Gestión donacion de Sangre</h1>
        <div>
            <a class="btn btn-primary" href="dar_alta_donante.php" role="button">Alta donantes</a>
            <a class="btn btn-primary" href="buscar_donantes.php" role="button">Buscar donantes</a>
            <a class="btn btn-primary" href="listar_donantes.php" role="button">Listar donantes</a>
            <a class="btn btn-primary" href="dar_alta_administrador.php" role="button">Nuevos administradores</a>
        </div>

        <?php include_once "footer.php" ?>

    </body>

</html>