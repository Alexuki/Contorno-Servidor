<!DOCTYPE html>
<html lang="es">

    <?php
        require_once "lib/bbdd.php";
        include_once "lib/utils.php";

        $dbName = "donacionTest";
        $con = crearConexion();
        seleccionarDB($con, $dbName);

        $fecha = null;
        $fechaMinima = null;
        $mensajes = [];

        if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
           
            $id = $_GET["id"];
            $ultimaDonacionRequest = getUltimaDonacion($con, $id);
            $donacion = $ultimaDonacionRequest[0] ? $ultimaDonacionRequest[1] : null;

            $fechaMinima = $donacion ? $donacion["fecha_proxima"] : null;
        }

        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            $id = $_POST["id"];
            $fechaMinima = $_POST["fechaMin"];
            $fecha = $_POST["fecha"];

            if ($fechaMinima != null && $fecha < $fechaMinima) {
                $mensajes[] = [false, "Debes seleccionar una fecha posterior a $fechaMinima"];
            } else {
                $mensajes[] = crearDonacion($con, $id, $fecha);
            }
        }
    ?>

    <head>
        <meta charset="utf-8">
        <title>Alta donación</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>

    <body>
        <h1>Lista donaciones</h1>


        <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">

            <input id="id" name="id" value="<?= $id ?>" hidden>
            <input id="fechaMin" name="fechaMin" value="<?= $fechaMinima ?>" type="date" hidden>


            <label for="fecha">Fecha:</label>
            <input id="fecha" name="fecha" type="date" value="<?= $fechaMinima ?>" required><br>


            <button type="submit" name="submit">Añadir donación</button>

        </form>

        <?php include_once "footer.php" ?>

    </body>

</html>