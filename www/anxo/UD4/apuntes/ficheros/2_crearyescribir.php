<?php

// Modo "w" - crear nuevo (o sobrescribir si existe)
$mifichero = fopen("nuevoarchivo.txt", "w") or die("Unable to open file!");
$txt = "Sabela\n";
fwrite($mifichero, $txt);
$txt = "Iván\n";
fwrite($mifichero, $txt);
fclose($mifichero);

// Leemos para mostrar
$contenido1 = file_get_contents("nuevoarchivo.txt");
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>2. Crear y escribir</title></head>
<body>
    <h1>Crear y escribir ficheros</h1>

    <h2>Paso 1: Modo "w" - crear nuevo archivo</h2>
    <p>Contenido de nuevoarchivo.txt:</p>
    <pre><?= htmlspecialchars($contenido1) ?></pre>

    <?php
    // Modo "w" de nuevo - ¡SOBREESCRIBE todo!
    $mifichero = fopen("nuevoarchivo.txt", "w") or die("Unable to open file!");
    $txt = "Miguel\n";
    fwrite($mifichero, $txt);
    $txt = "Juan\n";
    fwrite($mifichero, $txt);
    fclose($mifichero);

    $contenido2 = file_get_contents("nuevoarchivo.txt");
    ?>

    <h2>Paso 2: Modo "w" de nuevo - ¡sobrescribe todo!</h2>
    <p>Contenido tras reabrir en modo "w":</p>
    <pre><?= htmlspecialchars($contenido2) ?></pre>
    <p><em>Nota: Sabela e Iván han desaparecido</em></p>

    <?php
    // Modo "a" - añadir al final
    $mifichero = fopen("nuevoarchivo.txt", "a") or die("Unable to open file!");
    $txt = "Alejandro\n";
    fwrite($mifichero, $txt);
    $txt = "Julián\n";
    fwrite($mifichero, $txt);
    fclose($mifichero);

    $contenido3 = file_get_contents("nuevoarchivo.txt");
    ?>

    <h2>Paso 3: Modo "a" - añadir al final</h2>
    <p>Contenido tras añadir con modo "a":</p>
    <pre><?= htmlspecialchars($contenido3) ?></pre>

    <p><a href="3_subirarchivos.php">Ir a subir archivos</a></p>
</body>
</html>