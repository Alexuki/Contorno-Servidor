<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <title>Tienda</title>
    </head>

    <body>

        <?php
            require_once("lib/bbdd.php");

            $db = "db";
            $user = "root";
            $pass = "test";
            $dbName = "tiendaTest";
            $conexion = getConexion($db, $user, $pass);

            crearBD($conexion, $dbName);
            seleccionarBD($conexion,$dbName);
            crearTablaUsuarios($conexion);
            cerrarConexion($conexion);
        ?>

        <div class="container-fluid">
            <div class="row">
                <?php include_once("components/menu.php") ?>
                <main class="col-9" >
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h2>Tienda IES San Clemente</h2>
                    </div>
                    <div class="container">
                        <p>Aquí va el contenido </p>
                    </div>
                </main>
            </div>
        </div>
        <?php include_once("components/footer.php") ?>
    </body>
</html>