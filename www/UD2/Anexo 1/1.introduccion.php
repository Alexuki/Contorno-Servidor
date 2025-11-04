<html>

    <head>
        <title>Anexo 1</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container-fluid">
            <h1>Anexo 1</h1>

            <h3>Tarea 1. Introduccion</h3>
            <p>Localiza y corrige los errores de este programa PHP:</p>

            <p style="white-space: pre-line;">
                &lt;?php
                echo '¿Cómo estás?';
                echo 'Estoy bien, gracias.';
                ??&gt;
            </p>

            <?php
                echo '¿Cómo estás?' . '<br>';
                echo 'Estoy bien, gracias.' . '<br>';
            ?>

            <h3>Tarea 2. Variable, declaración.</h3>
            <p>Indica cuál de los siguientes son nombres de variables válidas e inválidos e indica por qué:</p>


            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Variable</th>
                        <th>Válida</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>valor</td>
                        <td>NO</td>
                        <td>Falta $</td>
                    </tr>
                    <tr>
                        <td>$_N</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$valor_actual</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$n</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$#datos</td>
                        <td>NO</td>
                        <td>Carácter no válido</td>
                    </tr>
                    <tr>
                        <td>$valorInicial0</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$proba,valor</td>
                        <td>NO</td>
                        <td>Carácter no válido</td>
                    </tr>
                    <tr>
                        <td>$2saldo</td>
                        <td>NO</td>
                        <td>Empezar por número</td>
                    </tr>
                    <tr>
                        <td>$n</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$meuProblema</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$meu Problema</td>
                        <td>NO</td>
                        <td>No admite espacios</td>
                    </tr>
                    <tr>
                        <td>$echo</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$m&m</td>
                        <td>NO</td>
                        <td>Carácter no válido</td>
                    </tr>
                    <tr>
                        <td>$registro</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$ABC</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$85 Nome</td>
                        <td>NO</td>
                        <td>Espacio y comenzar por número</td>
                    </tr>
                    <tr>
                        <td>$AAAAAAAAA</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$nome_apelidos</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$saldoActual</td>
                        <td>SI</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>$92</td>
                        <td>NO</td>
                        <td>Comenzar por número</td>
                    </tr>
                    <tr>
                        <td>$*143idade</td>
                        <td>NO</td>
                        <td>Carácter no válido</td>
                    </tr>
                </tbody>
            </table>


            <h3>Tarea 3. Funciones para trabajar con tipos de datos.</h3>

            <p>Busca en la documentación de PHP las funciones de <a href="http://www.php.net/manual/es/funcref.php">manejo de variables</a></p>
            
            <p>Comprueba el resultado devuelto por los siguientes fragmentos de código:</p>
            <p style="white-space: pre-line;">
                - $a = “true”; // imprime el valor devuelto por is_bool($a)...
                - $b = 0; // imprime el valor devuelto por is_bool($b)...; y se entra dentro de if($b)
                {...}
                - $c = “false”; // imprime el valor devuelto por gettype($c);
                - $d = “”; // el valor devuelto por empty($d);
                - $e = 0.0; // el valor devuelto por empty($e);
                - $f = 0; // el valor devuelto por empty($f);
                - $g = false; // el valor devuelto por empty($g);
                - $h; // el valor devuelto por empty($h);
                - $i = “0”; // el valor devuelto por empty($i);
                - $j = “0.0”; // el valor devuelto por empty($j);
                - $k = true; // el valor devuelto por isset($k);
                - $l = false; // el valor devuelto por isset($l);
                - $m = true; // el valor devuelto por is_numeric($m);
                - $n = “”; // el valor devuelto por is_numeric($n);
            </p>

            <?php
                $a = "true";
                $b = 0;
                $c = "false";
                $d = "";
                $e = 0.0;
                $f = 0;
                $g = false;
                $h;
                $i = "0";
                $j = "0.0";
                $k = true;
                $l = false;
                $m = true;
                $n = "";
                echo "is_bool(\$a): ".is_bool($a)."<br>";
                echo "is_bool(\$b): ".is_bool($b)."<br>";
                echo "test if(\$b): ";
                if($b){
                    echo "entra en if";
                }
                echo "<br>";
                echo "gettype(\$c): ".gettype($c)."<br>";
                echo "empty(\$d): ".empty($d)."<br>";
                echo "empty(\$e): ".empty($e)."<br>";
                echo "empty(\$f): ".empty($f)."<br>";
                echo "empty(\$g): ".empty($g)."<br>";
                echo "empty(\$h): ".empty($h)."<br>";
                echo "empty(\$i): ".empty($i)."<br>";
                echo "empty(\$j): ".empty($j)."<br>";
                echo "isset(\$k): ".isset($k)."<br>";
                echo "isset(\$l): ".isset($l)."<br>";
                echo "is_numeric(\$m): ".is_numeric($m)."<br>";
                echo "is_numeric(\$n): ".is_numeric($n)."<br>";
            ?>

            <h3>Tarea 4. Variables globales.</h3>
            Haz una página que ejecute la función phpinfo() y que muestre las principales variables de servidor mencionadas en teoría.
            
            <?php
                phpinfo(INFO_GENERAL);
                phpinfo(INFO_VARIABLES);
            ?>

            <h3>Tarea 5. Operadores.</h3>

            <p>1. Escribe un programa que pase de grados Fahrenheit a Celsius. Para pasar de Fahrenheit a Celsius se resta 32 a la temperatura, se multiplica por 5 y se divide entre 9. Declara en una variable el valor inicial de los grados y en otra el final.</p>

            <?php
                $fahrenheit = 40;
                $celsius = ($fahrenheit - 32) * 5 / 9;
                echo "$fahrenheit grados Fahrenheit equivalen a " . round($celsius, 2) . " grados Celsius"."<br>";
            ?>

            <p>2. Crea un programa en PHP que declare e inicialice dos variables x e y con los valores 20 y 10 respectivamente y muestre la suma, la resta, la multiplicación, la división y el módulo de ambas variables. (Optativo) Haz dos versiones de este ejercicios: guarda los resultados en nuevas variables; sin utilizar variables intermedias.</p>

            <p>Opción 1</p>
            <?php
                $x = 20;
                $y = 10;
                echo "\$x = 20 <br>";
                echo "\$y = 10 <br>";
                echo "\$x + \$y = " . ($x + $y) . "<br>";
                echo "\$x - \$y = " . ($x - $y) . "<br>";
                echo "\$x * \$y = " . ($x * $y) . "<br>";
                echo "\$x / \$y = " . ($x / $y) . "<br>";
                echo "\$x % \$y = " . ($x % $y) . "<br>";
                echo "<br>";
            ?>

            <p>Opción 2</p>
            <?php
                $x = 20;
                $y = 10;
                $suma = $x + $y;
                $resta = $x - $y;
                $multiplicacion = $x * $y;
                $division = $x / $y;
                $modulo = $x % $y;
                echo "\$x = 20 <br>";
                echo "\$y = 10 <br>";
                echo "\$x + \$y = " . $suma . "<br>";
                echo "\$x - \$y = " . $resta . "<br>";
                echo "\$x * \$y = " . $multiplicacion . "<br>";
                echo "\$x / \$y = " . $division . "<br>";
                echo "\$x % \$y = " . $modulo . "<br>";
                echo "<br>";
            ?>

            <p>3. Escribe un programa que imprima por pantalla los cuadrados de los 30 primeros números naturales.</p>
            <?php
                for($i = 1; $i <= 30; $i++) {
                    echo "Número $i => cuadrado = " . $i**2 . "<br>";
                }
                echo "<br>";
            ?>

            <p>4. Haz un programa php que calcule el área y el perímetro de un rectángulo (área=base*altura) y (perímetro=2*base+2*altura). Debes declarar las variables base=20 y altura=10.</p>
            <?php
                $base = 20;
                $altura = 10;
                $area = $base * $altura;
                $perimetro = 2 * ($base + $altura);

                echo "Base: $base, Altura: $altura <br>";
                echo "Área: $area <br>";
                echo "Perímetro: $perimetro <br>";
                echo "<br>";
            ?>

            <h3>Tarea 6. Cadenas.</h3>

            <p>1. Usando la instrucción "echo" crea un programa en PHP que muestre el mensaje: "Hola, Mundo!". Muéstralo en cursiva.</p>
            <?php
                echo "<i>Hola, Mundo!</i>";
                echo "<br>";
            ?>

            <p>2. Crea un programa en PHP que guarde en una variable tu nombre y lo muestre en negrita formando el siguiente mensaje: Bienvenido tu_nombre.</p>
            <?php
                $nombre = "Alex";
                echo "Bienvenido <b>$nombre</b><br>";
            ?>



        </div>

    </body>

</html>