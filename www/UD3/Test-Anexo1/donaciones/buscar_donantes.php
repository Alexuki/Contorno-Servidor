<!DOCTYPE html>
<html lang="es">

    <?php
        require_once "lib/bbdd.php";
        include_once "lib/utils.php";

        $dbName = "donacionTest";
        $con = crearConexion();
        seleccionarDB($con, $dbName);

        $grupos = [
            "0+" => "0+",
            "0-" => "0-",
            "A+" => "A+",
            "A-" => "A-",
            "B+" => "B+",
            "B-" => "B-",
            "AB+" => "AB+",
            "AB-" => "AB-"
        ];
        $cursorDonantes = null;
        $cp = $grupo = "";

        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
            $cp = !empty($_POST["codPostal"]) ? $_POST["codPostal"] : null;
            $grupo = !empty($_POST["grupoSanguineo"]) ? $_POST["grupoSanguineo"] : null;

            $result = buscaDonante($con, $cp, $grupo);
            $cursorDonantes = $result[0] ? $result[1] : null;
        }

    ?>

    <head>
        <meta charset="utf-8">
        <title>Buscar donante</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>

    <body>
        <h1>Buscar donante</h1>


        <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">

            <label for="codPostal">Código Postal:</label>
            <input id="codPostal" name="codPostal" type="number" value="<?= $cp ?>"><br>

            <label for="grupoSanguineo">Grupo sanguíneo:</label>
            <select id="grupoSanguineo" name="grupoSanguineo" >
                <option value="" <?= empty($grupo) ? "selected" : "" ?>>Grupo sanguíneo</option>
                <?foreach ($grupos as $value => $text) : ?>
                    <option value="<?= $value ?>" <?= $value == $grupo ? "selected" : "" ?>><?= $text ?></option>
                <? endforeach ?>
            </select><br>

            <button type="submit" name="submit">Buscar donante</button>

        </form>

        <?php 
            if($cursorDonantes) { ?>

            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Edad</th>
                    <th>Grupo Sanguíneo</th>
                    <th>Código Postal</th>
                    <th>Teléfono Móvil</th>
                </tr>

                <?php 
                    while ($row = $cursorDonantes->fetch()) { ?>
                    <tr>
                        <th><?= $row["nombre"] ?></th>
                        <th><?= $row["apellidos"] ?></th>
                        <th><?= $row["edad"] ?></th>
                        <th><?= $row["grupo_sanguineo"] ?></th>
                        <th><?= $row["cod_postal"] ?></th>
                        <th><?= $row["movil"] ?></th>
                    </tr>
                <?php
                    } ?>

            </table>

        <?php    
            }
        ?>



        <?php include_once "footer.php" ?>

    </body>

</html>