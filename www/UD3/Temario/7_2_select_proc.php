<?php

    /** MYSQL Procedimental **/

    $servername = "db";
    $user = "root";
    $pass = "test";
    $dbName = "myDBProc";

    //1. Crear conexión
    $con = mysqli_connect($servername, $user, $pass, $dbName);

    //2. Comprobar conexión
    if(!$con) {
        die("Fallo de conexión".mysqli_connect_error());
    }
    echo "Conexión procedimental correcta. <br>";

    //3. Consulta
    $sql = "SELECT id, nombre, apellido FROM clientes";
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo $row["id"] . " - " . $row["nombre"] . " " . $row["apellido"] . "<br>";
        }

    } else {
        echo "No hay resultados <br>";
    }


    //6. Cerrar conexión
    mysqli_close($con);
    echo "Conexión cerrada. <br>";

?>