<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>1. Leer ficheros</title></head>
<body>
    <h1>Leer ficheros</h1>

    <!-- readfile() -->
    <h2>Método 1: readfile()</h2>
    <pre><?php readfile("webdictionary.txt"); ?></pre>

    <!-- fopen() + fread() + fclose() -->
    <h2>Método 2: fopen() + fread() + fclose()</h2>
    <?php
    $mifichero = fopen("webdictionary.txt", "r") or die("Unable to open file!");
    echo "<pre>" . fread($mifichero, filesize("webdictionary.txt")) . "</pre>";
    fclose($mifichero);
    ?>

    <!-- fgets() - una sola línea -->
    <h2>Método 3: fgets() - solo primera línea</h2>
    <?php
    $mifichero = fopen("webdictionary.txt", "r") or die("Unable to open file!");
    echo "<pre>" . fgets($mifichero) . "</pre>";
    fclose($mifichero);
    ?>

    <!-- feof() + fgets() - todas las líneas -->
    <h2>Método 4: feof() + fgets() - línea a línea</h2>
    <?php
    $mifichero = fopen("webdictionary.txt", "r") or die("Unable to open file!");
    while (!feof($mifichero)) {
        echo fgets($mifichero) . "<br>";
    }
    fclose($mifichero);
    ?>

    <!-- fgetc() - carácter por carácter -->
    <h2>Método 5: fgetc() - carácter por carácter</h2>
    <?php
    $mifichero = fopen("webdictionary.txt", "r") or die("Unable to open file!");
    while (!feof($mifichero)) {
        echo fgetc($mifichero);
    }
    fclose($mifichero);
    ?>

    <p><a href="2_crearyescribir.php">Ir a crear y escribir ficheros</a></p>
</body>
</html>