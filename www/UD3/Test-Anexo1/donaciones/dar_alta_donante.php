<!DOCTYPE html>
<html lang="es">

    <?php
        require_once "lib/bbdd.php";
        include_once "lib/utils.php";

        $dbName = "donacionTest";
        $con = crearConexion();
        seleccionarDB($con, $dbName);

        $nombre = $apellidos = $edad = $grupoSanguineo = $codPostal = $movil = "";
        $mensajes = [];
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


        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["alta"])) {
           
            $campos = $_POST; // Es una copia profunda
            unset($campos["alta"]);

            foreach($campos as $c => $v) {
                $v = ajustarInput($v);
                
                if (empty($v)) {
                    $mensajes[] = [false, "$c no puede estar vacío."];
                }
                if ($c == "edad" && $v < 18) {
                    $mensajes[] = [false, "$c debe ser mayor de 18 años"];
                }
                if ($c == "movil" && strlen((string)$v) != 9) {
                    $mensajes[] = [false, "$c debe tener 9 dígitos"];
                }

                $$c = $v;           
            }

            if(count($mensajes) == 0) { 
                $mensajes[] = crearDonante($con, $nombre, $apellidos, $edad, $grupoSanguineo, $codPostal, $movil );
            }
        }
    ?>

    <head>
        <meta charset="utf-8">
        <title>Alta donante</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>

    <body>
        <h1>Alta donante</h1>

        <?= mostrarMensajeResultadoSql($mensajes) ?>

        <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
            <label for="nombre">Nombre:</label>
            <input id="nombre" name="nombre" value="<?= $nombre ?>" required><br>

            <label for="apellidos">Apellidos:</label>
            <input id="apellidos" name="apellidos" value="<?= $apellidos ?>" required><br>

            <label for="edad">Edad:</label>
            <input id="edad" name="edad" type="number" value="<?= $edad ?>" required><br>

            <label for="grupoSanguineo">Grupo sanguíneo:</label>
            <select id="grupoSanguineo" name="grupoSanguineo" required>
                <?foreach ($grupos as $value => $text) : ?>
                    <option value="<?= $value ?>" <?= $grupoSanguineo == $value ? "selected" : "" ?>><?= $text ?></option>
                <? endforeach ?>
            </select><br>

            <label for="codPostal">Código postal:</label>
            <input id="codPostal" name="codPostal" type="number" value="<?= $codPostal ?>" required><br>

            <label for="movil">Teléfono móvil:</label>
            <input id="movil" name="movil" type="number" value="<?= $movil ?>" required><br>

            <button type="submit" name="alta">Alta</button>

        </form>

        <?php include_once "footer.php" ?>

    </body>

</html>