<html>

    <head>
        <title>Formulario 3.1</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container-fluid">
            <?php
                $name = $_GET["nombre"];
                $surname = $_GET["apellido"];


                echo "<ul>";
                echo "<li>" . "Nombre: " . $name . "</li>";
                echo "<li>" . "Apellidos: " . $surname . "</li>";
                echo "<li>" . "Nombre y apellidos: " . "$name $surname" . "</li>";
                echo "<li>" . "Tu nombre tiene " . strlen($name) . " caracteres. " . "</li>";
                echo "<li>" . "Los 3 primeros caracteres de tu nombre son: " . mb_substr($name, 0, 3) . "</li>";
                echo "<li>" . "La letra A fue encontrada en tus apellidos en la posición: " . stripos($surname, "A") . "</li>";
                echo "<li>" . "Tu nombre contiene " . substr_count(strtolower($name), "a") . " caracteres 'A'." . "</li>";
                echo "<li>" . "Tu nombre en mayúsculas es: " . mb_strtoupper($name) . "</li>";
                echo "<li>" . "Tus apellidos en minúsculas son: " . mb_strtolower($surname) . "</li>";
                echo "<li>" . "Tu nombre y apellido en mayúsculas: " . mb_strtoupper($name . " " . $surname) . "</li>";
                echo "<li>" . "Tu nombre y apellido en mayúsculas: " . strtoupper($name . " " . $surname) . "</li>";
                echo "<li>" . "Tu nombre escrito al revés es: " . strrev($name) . "</li>";
                echo "</ul>";
            ?>
        </div>
    </body>
</html> 