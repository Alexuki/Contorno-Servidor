<?php
    $ruta_fichero = "diccionario.txt";
    $longitud_fichero = filesize($ruta_fichero);
    $ruta_nuevo_fichero = "nuevoFichero.txt";
?>
<html>
    <head>
        <title>Ficheros</title>
    </head>
    <body>
        <h2>LECTURA DE FICHEROS</h2>
        <p>Lectura de fichero con readfile</p>
        <?= readfile($ruta_fichero) ?>
        <hr>
        <p>Lectura de fichero con fopen y fread</p>
        <?php
            $puntero = fopen($ruta_fichero, "r") or die("Imposible abrir fichero!!");
            echo fread($puntero, $longitud_fichero);
            fclose($puntero);
        ?>
        <hr>
        <p>Lectura línea a línea hasta alcanzar EOF</p>
        <?php
            $puntero = fopen($ruta_fichero, "r") or die("Imposible abrir fichero!!");
            while(!feof($puntero)) {
                echo fgets($puntero) . "<br>";
            }
            fclose($puntero);
        ?>
        <hr>
        <p>Lectura carácter a carácter hasta alcanzar EOF</p>
        <?php
            $puntero = fopen($ruta_fichero, "r") or die("Imposible abrir fichero!!");
            while(!feof($puntero)) {
                echo fgetc($puntero);
            }
            fclose($puntero);
        ?>
        <hr>
        <h2>ESCRITURA DE FICHEROS</h2>
        <p>Escritura de fichero con fopen y fwrite</p>
        <?php
            $puntero = fopen($ruta_nuevo_fichero, "w") or die("Imposible abrir fichero!!");
            $txt = "Primera Línea \n";
            fwrite($puntero, $txt);
            $txt = "Segunda Línea \n";
            fwrite($puntero, $txt);
            fclose($puntero);
        ?>
        <p>Contenido del nuevo fichero</p>
        <?php
        $puntero = fopen($ruta_nuevo_fichero, "r");
        while(!feof($puntero)) {
            echo fgets($puntero) . "<br>";
        }
        fclose($puntero);
        ?>
        <hr>
        <p>Sobreescritura del fichero</p>
        <?php
            $puntero = fopen($ruta_nuevo_fichero, "w") or die("Imposible abrir fichero!!");
            $txt = "Primera Línea NUEVA \n";
            fwrite($puntero, $txt);
            $txt = "Segunda Línea NUEVA \n";
            fwrite($puntero, $txt);
            fclose($puntero);
        ?>
        <p>Contenido del nuevo fichero</p>
        <?php
        $puntero = fopen($ruta_nuevo_fichero, "r");
        while(!feof($puntero)) {
            echo fgets($puntero) . "<br>";
        }
        fclose($puntero);
        ?>
        <hr>
        <p>Añadir datos al final del fichero</p>
        <?php
            $puntero = fopen($ruta_nuevo_fichero, "a") or die("Imposible abrir fichero!!");
            $txt = "Primera Línea AÑADIDA \n";
            fwrite($puntero, $txt);
            $txt = "Segunda Línea AÑADIDA \n";
            fwrite($puntero, $txt);
            fclose($puntero);
        ?>
        <p>Contenido del nuevo fichero</p>
        <?php
        $puntero = fopen($ruta_nuevo_fichero, "r");
        while(!feof($puntero)) {
            echo fgets($puntero) . "<br>";
        }
        fclose($puntero);
        ?>

    </body>
</html>