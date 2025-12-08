<!DOCTYPE html>
<html lang="es">

    <?php
        require_once "lib/bbdd.php";
        include_once "lib/utils.php";

        $dbName = "donacionTest";
        $con = crearConexion();
        seleccionarDB($con, $dbName);
        $donacionesCursor = null;


        
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["id"];
            $nombre = $_POST["nombre"];

            $donacionesResult = listaDonaciones($con, $id);
            $donacionesCursor = $donacionesResult[1];
        }
    ?>

    <head>
        <meta charset="utf-8">
        <title>Lista donaciones</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>

    <body>
        <h1>Lista de donaciones de <?= $nombre ?></h1>

         <table class='table table-hover table-sm text-center'>

            <thead class='thead-light'>
                <tr>
                    <th scope='col'>Nombre</th>
                    <th scope='col'>Apellidos</th>
                    <th scope='col'>Fecha de donación</th>
                    <th scope='col'>Fecha próxima donación</th>
                </tr>
            </thead>

            <tbody>
                <?php if($donacionesCursor) : ?>

                    <?php while ($row = $donacionesCursor->fetch()) : ?>
                        <tr>
                            <td><?= $row["nombre"] ?></td>
                            <td><?= $row["apellidos"] ?></td>
                            <td><?= $row["fecha"] ? date("d/m/Y", strtotime($row["fecha"])) : 'N/A' ?></td>
                            <td><?= $row["fecha_proxima"] ? date("d/m/Y", strtotime($row["fecha_proxima"])) : 'N/A' ?></td>      
                        </tr>
                    <?php endwhile ?>
                    <?php $donacionesCursor->closeCursor() ?>

                <?php endif ?>

                
            </tbody>

        </table>

        <?php include_once "footer.php" ?>

    </body>

</html>