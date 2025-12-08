<?php

    //define("VOCALES", ["a", "e", "i", "o", "u", "á", "é", "í", "ó", "ú"]);
    const VOCALES = ["a", "e", "i", "o", "u", "á", "é", "í", "ó", "ú"];
    const LETRAS_CONTROL = 'TRWAGMYFPDXBNJZSQVHLCKE';

    function esDigito($char) {
        if(ctype_digit($char) && intval($char)>= 0 && intval($char) < 9) {
            echo "Es un dígito";
        } else {
            echo $char . " no es un dígito";
        }
    }

    function longitudCadena($texto) {
        return mb_strlen($texto);
    }

    function potencia($a, $b) {
        return  $a ** $b;
    }

    function esVocal($char) {
        return (ctype_alpha($char) && in_array(mb_strtolower($char), VOCALES));
    }

    function paridad($num) {
        return $num % 2 == 0 ? "Es par" : "Es impar";
    }

    function timeZonePorDefecto() {
        echo date_default_timezone_get();
    }

    function salidaPuestaSol($lat = null, $long = null) {
        // Si no se pasan parámetros, obtener de configuración
        if ($lat === null) {
            $lat = ini_get('date.default_latitude') ?: 42.8782; // Santiago por defecto
        }
        if ($long === null) {
            $long = ini_get('date.default_longitude') ?: -8.5448;
        }

        // Calcular amanecer y atardecer
        $sol_info = date_sun_info(time(), $lat, $long);
        echo "Amanecer: " . date('H:i:s', $sol_info['sunrise']) . "<br>";
        echo "Atardecer: " . date('H:i:s', $sol_info['sunset']) . "<br>";
    }

    function comprobarNif($nif) {
        if(strlen($nif) != 9) {
            return false;
        }
        $num = substr($nif, 0 , -1);
        if (ctype_digit($num)) {
            return false;
        }
        $numero = intval($num);
        $letra = substr($nif, -1);
        $control = LETRAS_CONTROL[$numero % 23];

        return $letra == $control;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>4. Funciones</title>
    </head>
    <body>
        <p>Función esDigito("3")</p>
        <?= esDigito("3") ?>
        <p>Función longitudCadena("Cuántas letras?")</p>
        <?= longitudCadena("Cuántas letras?") ?>
        <p>Función potencia(2, 3)</p>
        <?= potencia(2, 3) ?>
        <p>Función esVocal("i")</p>
        <?=  esVocal("i") ?>
        <p>Función esVocal("á")</p>
        <?=  esVocal("á") ?>
        <p>Función esVocal("b")</p>
        <?=  esVocal("b") ?>
        <p>Función paridad(8)</p>
        <?=  paridad(8) ?>
        <p>Función paridad(15)</p>
        <?=  paridad(15) ?>
        <p>Función timeZonePorDefecto()</p>
        <?=  timeZonePorDefecto() ?>
        <p>Función salidaPuestaSol()</p>
        <?=  salidaPuestaSol() ?>
        <p>Función salidaPuestaSol(42.8782, -8.5448)</p>
        <?=  salidaPuestaSol(42.8782, -8.5448) ?>
        <p>Función comprobarNif("12345678Z")</p>
        <?=  comprobarNif("12345678Z") ?>
        <p>Función comprobarNif("12345678A")</p>
        <?=  comprobarNif("12345678A") ?>
        <p>Función comprobarNif("12345678")</p>
        <?=  comprobarNif("12345678") ?>
    </body>
</html>