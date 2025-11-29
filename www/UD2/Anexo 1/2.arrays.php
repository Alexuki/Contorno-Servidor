<html>

    <head>
        <title>Anexo 1</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container-fluid">
            <h1>Anexo 1</h1>

            <h3>Tarea 2. Uso de arrays</h3> 
            <p>1. Almacena en un array los 10 primeros números pares. Imprímelos cada uno en una línea.</p>

            <?php
                $pares = [];
                // si no se especifica ninguna clave, se toma el máximo de los índices
                // int existentes, y la nueva clave será ese valor máximo más 1
                for ($i = 2; $i <= 10; $i+=2) {
                    $pares[] = $i;
                }
                for ($i = 0; $i < count($pares); $i++) {
                    echo "$pares[$i] <br>";
                }
                echo "<br>";
            ?>

            <p>2. Imprime los valores del array asociativo siguiente usando un foreach:</p>
            <p style="white-space: pre-line;" > 
                $v[1]=90;
                $v[10] = 200;
                $v[‘hola’]=43;
                $v[9]=’e’;
            </p>
            <?php
                $v[1]=90;
                $v[10] = 200;
                $v['hola']=43;
                $v[9]='e';

                foreach($v as $item) {
                    echo "$item <br>";
                }
                echo "<br>";
            ?>

            <h3>Tarea 2. Arrays multidimensionales</h3> 
            <p>Almacena la información en un array multidimensional e imprímela usando bucles.</p>
            <p style="white-space: pre-line;" >
                ◦ John
                    email: john@demo.com
                    website: www.john.com
                    age: 22
                    password: pass
                • Anna
                    email: anna@demo.com
                    website: www.anna.com
                    age: 24
                    password: pass
                • Peter
                    email: peter@mail.com
                    website: www.peter.com
                    age: 42
                    password: pass
                • Max
                    email: max@mail.com
                    website: www.max.com
                    age: 33
                    password: pass 
            </p>

            <?php
                $persons = [
                    "John" => [
                        "email" => "john@demo.com",
                        "website" => "www.john.com",
                        "age" => 22,
                        "password" => "pass"
                    ],
                    "Anna" => [
                        "email" => "anna@demo.com",
                        "website" => "www.anna.com",
                        "age" => 24,
                        "password" => "pass"
                    ],
                    "Peter" => [
                        "email" => "peter@mail.com",
                        "website" => "www.peter.com",
                        "age" => 42,
                        "password" => "pass"
                    ],
                    "Max" => [
                        "email" => "max@mail.com",
                        "website" => "www.max.com",
                        "age" => 33,
                        "password" => "pass"
                    ]    
                ];

                foreach ($persons as $p => $data) {
                    echo "{$p}:<br>";
                    foreach($data as $key => $value) {
                        echo "{$key}: $value <br>";
                    }
                }
                echo "<br>";
            ?>

            <h3>Tarea 3. Uso de Arrays</h3> 
            <p> Realiza los siguientes pasos. Utiliza <a href="https://www.php.net/manual/es/ref.array.php">funciones de matriz</a>.</p>

            <p>1. Crea una matriz con 30 posiciones y que contenga números aleatorios entre 0 y 20 (inclusive). Uso de la función <a href="https://www.php.net/manual/es/function.rand.php">rand</a>. Imprime la matriz creada anteriormente.</p>

            <?php
                $array = [];
                for ($i = 0; $i < 30; $i++) {
                    array_push($array, rand(0, 20));
                }
                print_r($array);
                echo "<br>";
            ?>

            <p>2. Crea una matriz con los siguientes datos: `Batman`, `Superman`, `Krusty`, `Bob`, `Mel` y `Barney`.</p>
            <ul>
                <li>Elimina la última posición de la matriz.</li>
                <li>Imprime la posición donde se encuentra la cadena 'Superman'.</li>
                <li>Agrega los siguientes elementos al final de la matriz: `Carl`, `Lenny`, `Burns` y `Lisa`.</li>
                <li>Ordena los elementos de la matriz e imprima la matriz ordenada.</li>
                <li>Agrega los siguientes elementos al comienzo de la matriz: `Apple`, `Melon`, `Watermelon`.</li>
            </ul>

            <?php
                $array = array("Batman", "Superman", "Krusty", "Bob", "Mel", "Barney");
                array_pop($array);
                $index = array_search("Superman", $array);
                echo "Posición de Superman: $index <br>";
                array_push($array, "Carl", "Lenny", "Burns", "Lisa");
                sort($array);
                for ($i = 0; $i < count($array); $i++) {
                    echo "$array[$i]";
                    if ($i < count($array) - 1) {
                        echo ", ";
                    }
                }
                echo "<br>";

                array_unshift($array, "Apple", "Melon", "Watermelon");
                print_r($array);
                echo "<br>";
            ?>

            <p>3. Crea una copia de la matriz con el nombre `copia` con elementos del 3 al 5. Agrega el elemento `Pera` al final de la matriz.</p>
            <?php
                $copia = array_slice($array, 2, 3);
                array_push($copia, "Pera");
                print_r($copia);
                echo "<br>";
            ?>

            <h3>Tarea 4. Uso de arrays y Strings</h3> 
            <p> En un <strong>string</strong>, tenemos almacenados varios datos agrupados en ciudad, país y continente. El formato es `ciudad,pais,continente` y cada grupo *ciudad-pais-continente* se separa con un `;`.</p>
            <p style="white-space: pre-line;" >
                $informacion = "Tokyo,Japan,Asia;Mexico City,Mexico,North America;New York City,USA,North America;Mumbai,India,Asia;Seoul,Korea,Asia;Shanghai,China,Asia;Lagos,Nigeria,Africa;Buenos Aires,Argentina,South America;Cairo,Egypt,Africa;London,UK,Europe";
            </p>
            <p>Crea una aplicación PHP que imprima toda la información almacenada en el *string* en una tabla con 3 columnas.</p>

            <?php
                $informacion = "Tokyo,Japan,Asia;Mexico City,Mexico,North America;New York City,USA,North America;Mumbai,India,Asia;Seoul,Korea,Asia;Shanghai,China,Asia;Lagos,Nigeria,Africa;Buenos Aires,Argentina,South America;Cairo,Egypt,Africa;London,UK,Europe";
            
                $places = explode(";", $informacion);
            ?>

            <table class="table table-bordered mw-50 w-50">
                <tr>
                    <td>Ciudad</td>
                    <td>País</td>
                    <td>Continente</td>
                </tr>
                <?php
                    foreach($places as $p) {
                        $parts = explode(",", $p);
                        echo "<tr><td>$parts[0]</td><td>$parts[1]</td><td>$parts[2]</td></tr>";
                    }
                ?>
            </table>

            <br>
        
            <p>Con la información anterior, realiza las seguintes tareas:</p>
            <p>1. Crea la estrutura de datos y almacena toda la información anterior en un array.</p>
            <p>2. Utilizando la instrución `foreach` e etiquetas HTML, imprime toda a información almacenada para que apareza de forma similar al ejemplo mostrado.</p>
            <?php
                $cities = [];
                foreach($places as $place) {
                    $parts = explode(",", $place);
                    $cities[] = [
                        "city" => $parts[0],
                        "country" => $parts[1],
                        "continent" => $parts[2]
                    ];
                }
            ?>
            <table class="table table-bordered mw-50 w-50">
                <tr><td>Ciudad</td><td>País</td><td>Continente</td></tr>
                <?php
                    foreach($cities as $c) {
                        echo "<tr><td>{$c["city"]}</td><td>{$c["country"]}</td><td>{$c["continent"]}</td></tr>";
                    }
                ?>
            </table>
            <br>

    </body>

</html>