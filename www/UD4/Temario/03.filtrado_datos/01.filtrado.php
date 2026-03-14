<html lang="es">
    <head>
        <title>Validación, saneamiento y variables de entorno</title>
        <meta charset="utf-8">
    </head>
    <body>

        <h2>Validación de datos</h2>
        <?php
            $email = "email@example.com";
            $email2 = "(email@example.com)";
            echo "Validación de email: " . $email . "<br>";
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Email válido: " . $email . "<br>";
            } else {
                echo "Email no válido: " . $email . "<br>";
            }
            echo "Validación de email: " . $email2 . "<br>";
            if (filter_var($email2, FILTER_VALIDATE_EMAIL)) {
                echo "Email válido: " . $email2 . "<br>";
            } else {
                echo "Email no válido: " . $email2 . "<br>";
            }
        ?>
        <hr>

        <h2>Saneamiento de datos</h2>
        <?php
            echo "Saneamiento de email: " . $email2 . "<br>";
            $email_saneado = filter_var($email2, FILTER_SANITIZE_EMAIL);
            
            echo "Email original: " . $email2 . "<br>";
            echo "Email saneado: " . $email_saneado . "<br>";
        ?>
        <hr>

        <h2>Filtrado de lista</h2>
        <?php
            $lista = [
                "nombre" => "Nombre Apellido",
                "email" => "(email@example.com)",
                "edad" => "70"
            ];

// NOTA: FILTER_SANITIZE_STRING está deprecado en PHP 8.1.0

            $filtros = [
                "nombre" => FILTER_DEFAULT,
                "email" => FILTER_SANITIZE_EMAIL,
                "edad" => [
                    "filter" => FILTER_VALIDATE_INT,
                    "options" => [
                        "min_range" => 18,
                        "max_range" => 65
                    ]
                ]
            ];

            $resultado = filter_var_array($lista, $filtros);
            echo "Lista original: " . "<br>";
            print_r($lista);
            echo "<br>";
            echo "Lista filtrada: " . "<br>";
            print_r($resultado);
        ?>
        <hr>

        <h2>Acceso a variables de entorno</h2>
        <?php
            echo "Variables de entorno definidas en el fichero .env" . "<br>";
            echo "APP_ENV: " . $_ENV["APP_ENV"] . "<br>";
            echo "DATABASE_NAME: " . $_ENV["DATABASE_NAME"] . "<br>";
        ?>
        
    </body>
</html>