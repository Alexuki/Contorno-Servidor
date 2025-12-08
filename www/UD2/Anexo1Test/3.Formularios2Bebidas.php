<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title>Formulario Bebidas</title>
    </head>

    <body>
        <?php
            if ($_SERVER["REQUEST_METHOD"]== "POST" && !empty($_POST)) {
                $bebida = $_POST["bebida"];
                $cantidad = $_POST["cantidad"];
                $precio = 0;
                $nombre_bebida = "";

                switch ($bebida) {
                    case "cocaCola": {
                        $nombre_bebida = "Coca Cola";
                        $precio = 1;
                        break;
                    }
                    case "pepsiCola": {
                        $nombre_bebida = "Pepsi Cola";
                        $precio = 0.8;
                        break;
                    }
                } 
            }
        ?>

        <p>Respuesta usando sintaxis de llaves en PHP</p>
        <?php if (!empty($nombre_bebida)) { ?>
            <p>Pediste <?= $cantidad ?> botellas de <?= $nombre_bebida ?></p>
            <p>Precio total: <?= number_format(($cantidad * $precio), 2, ".", "") ?> Euros.</p>
        <?php } else { ?>
            <p>Datos incorrectos.</p>
        <?php } ?>

        <br><br>
        <p>Respuesta usando sintaxis alternativa en PHP</p>
        <?php if(!empty($nombre_bebida)): ?>
            <p>Pediste <?= $cantidad ?> botellas de <?= $nombre_bebida ?></p>
            <p>Precio total: <?= number_format(($cantidad * $precio), 2, ".", "") ?> Euros.</p>
        <?php else: ?>
            <p>Datos incorrectos.</p>
        <? endif ?>



    </body>
</html>