<html>
    <head>
    <title>4. Funciones</title>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid">
            <h1>4. Funciones</h1>
            <br />
            <h3>Tarea 1. Uso de funciones</h3>
            <p style="white-space: pre-line;">
                Realiza los seguintes pasos:
                1. Crea una función que reciba un carácter e imprima si el carácter es un dígito entre 0 y 9.
                2. Crea una función que reciba un string y devuelva su longitud.
                3. Crea una función que reciba dos números a y b y devuelva el número a elevado a b.
                4. Crea una función que reciba un carácter y devuelva true si el carácter es una vocal.
                5. Crea una función que reciba un número y devuelva si el número es par o impar.
                6. Crea una función que reciba un string y devuelva el string en maiúsculas.
                7. Crea una función que imprima la zona horaria (timezone) por defecto utilizada en PHP.
                8. Crea una función que imprima la hora a la que sale y se pone el sol para la localicación por defecto.
                Debes comprobar como ajustar las coordenadas (latitud y longitud) predeterminadas de tu servidor.
            </p>
            
            <?php
                function isDigit($text) {
                    if (ctype_digit($text) && intval($text) >= 0 && intval($text) < 10) {
                        echo "El texto introducido es un dígito entre 0 y 9: $text <br>";
                    } else {
                        echo "El texto introducido no es un dígito entre 0 y 9: $text <br>";
                    }
                }

                function stringLength($text) {
                    return mb_strlen($text);
                }

                function power($a, $b) {
                    return $a ** $b;
                }

                function isVowel($char) {
                    $vowels = ["a", "e", "i", "o", "u"];
                    $len = mb_strlen($char);

                    if ($len == 1 && in_array(mb_strtolower($char), $vowels) ) {
                        echo "El carácter introducido es una vocal: $char <br>";
                    } else if ($len > 1){
                        echo "Debes introducir un solo carácter <br>";
                    } else {
                        echo "El carácter introducido no es una vocal: $char <br>";
                    }
                }

                function evenOrOdd($num) {
                    if ($num % 2 == 0) {
                        echo "El número es par: $num <br>";
                    } else {
                        echo "El número es impar: $num <br>";
                    }
                }

                function toUpper($text) {
                    return mb_strtoupper($text);
                }

                function timeZone() {
                    $timezone = date_default_timezone_get();
                    echo "La zona horaria por defecto es: $timezone <br>";
                }

                function sunRiseAndSet($lat = 42.8782, $long = -8.5448)
                {
                    $now = time();
                    $info = date_sun_info($now, $lat, $long);
                
                    echo 'Hora de salida del sol: ' . date("H:i:s", $info['sunrise']) . '<br>';
                    echo 'Hora de puesta del sol: ' . date("H:i:s", $info['sunset']) . '<br>';
                }

                echo '<h2>Resultados</h2>';
                echo "<br>";
                echo "ATENCIÓN AL DESORDEN DE LOS TEXTOS POR LOS echo DE LAS FUNCIONES";
                echo "isDigit('5'): " . isDigit("5");
                echo "isDigit('a'): " . isDigit("a");
                echo "stringLength('Esto es una cadena'): " . stringLength("Esto es una cadena") . "<br>";
                echo "power(2, 3): " . power(2, 3) . "<br>";
                echo "isVowel('A'): " . isVowel("A");
                echo "isVowel('b'): " . isVowel("b");
                echo "isVowel('cd'): " . isVowel("b");
                echo "evenOrOdd('4'): " . evenOrOdd(4);
                echo "evenOrOdd('7'): " . evenOrOdd(7);
                echo "toUpper('Mi texto'): " . toUpper("Mi texto") . "<br>";
                echo "toUpper('Mi texto'): " . toUpper("Mi texto") . "<br>";
                echo timeZone();
                echo sunRiseAndSet();     
            ?>

            <br />
            <h3>Tarea 2. Programa DNI</h3> 

            <?php

                function comprobar_nif($nif) {
                    $letras_control = "TRWAGMYFPDXBNJZSQVHLCKE";

                    if(stringLength($nif) > 9) {
                        return false;
                    }

                    $num = substr($nif, 0 , 8);
                    $letra = substr($nif, -1);

                    if (!ctype_digit($num)) {
                        return false;
                    }

                    $indice = intval($num) % 23;
                    $letra_calculada = $letras_control[$indice];

                    return strtoupper($letra) == $letra_calculada;
                    
                }

                $nif = "12345678Z";
                echo "NIF: 12345678Z <br>" ;
                echo "Índice letra: " . 12345678 % 23 . "<br>" ;

                if (comprobar_nif($nif)) {
                    echo "El NIF $nif es correcto. <br>";
                } else {
                    echo "El NIF $nif no es correcto. <br>";
                }
               
            ?>


        </div>  
    </body>
</html>
