<html>

    <head>
        <title>Formulario 3.2</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container-fluid">
            <?php

                if($_SERVER["REQUEST_METHOD"] == "POST") {

                    $bebida = $_POST["bebida"];
                    $cantidad = $_POST["cantidad"];
                    $precio = 0;

                    switch($bebida) {
                        case "Coca Cola":
                            $precio = 1.00;
                            break;
                        case "Pepsi Cola":
                            $precio = 0.80;
                            break;
                        case "Fanta Naranja":
                            $precio = 0.90;
                            break;
                        case "Trina Manzana":
                            $precio = 1.10;
                            break;
                        default:
                            echo "Bebida no válida";
                            exit;
                    }

                    echo "PEDIDO: $cantidad x $bebida = " . ($cantidad * $precio) . " €";

                }
            ?>
            <a href="3.formularios.php" class="btn btn-info">Volver</a>
        </div>
    </body>
</html> 