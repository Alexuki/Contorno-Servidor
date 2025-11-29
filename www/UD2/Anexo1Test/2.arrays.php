<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Práctica</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>

    <body>
        <h3>Tarea 2. Arrays multidimensionales</h3>
        <?php
            $persons = [
                [
                    "name" => "John",
                    "info" => [
                        "email" => "john@demo.com",
                        "web" => "www.j.com",
                        "age" => 22,
                        "pass" => "pass"
                    ]
                ],
                [
                    "name" => "Anna",
                    "info" => [
                        "email" => "anna@demo.com",
                        "web" => "www.a.com",
                        "age" => 24,
                        "pass" => "pass"
                    ]
                ]

            ];
            foreach($persons as $p) {
                echo "Nombre: " . $p["name"] . "<br>";
                echo "Datos: " . "<br>";
                foreach ($p["info"] as $k => $v) {
                    echo $k . " => " . $v . "<br>";
                }
                echo "------------------<br>";
            }

            for($i=1; $i<=30; $i++) {
                $array[] = rand(0, 20);
            }
            print_r($array);
            echo "<br>";

            $characters = ["Batman", "Superman", "Mel"];
            print_r ($characters);
            echo "<br>";

            array_pop($characters);
            echo array_search("Superman", $characters);
            echo "<br>";

            array_push($characters, "Carl", "Lenny");
            asort($characters);

            array_unshift($characters, "Apple", "Melon");
            print_r ($characters);
            echo "<br>";

            $copia = array_slice($characters, 2, 3);
            array_push($copia, "Pera");
            print_r ($copia);
            echo "<br>";

            $informacion = "Tokyo,Japan,Asia;Mexico City,Mexico,North America;New York City,USA,North America;Mumbai,India,Asia;Seoul,Korea,Asia;Shanghai,China,Asia;Lagos,Nigeria,Africa;Buenos Aires,Argentina,South America;Cairo,Egypt,Africa;London,UK,Europe";

            $places = explode(";", $informacion);



        ?>
        <table class="table table-bordered w-50">
            <thead class="table-light">
                <tr>
                <th>Ciudad</th>
                <th>País</th>
                <th>Continente</th>
            </tr>
            </thead>
            <tbody>
                <?php
                    foreach($places as $place) {
                        $parts = explode(",", $place);
                        echo "<tr>";
                        echo "<td>" . $parts[0] . "</td>";
                        echo "<td>" . $parts[1] . "</td>";
                        echo "<td>" . $parts[2] . "</td>";
                        echo "<tr>";
                    }
                ?>
            </tbody>

        </table>
        
    </body>

</html>

