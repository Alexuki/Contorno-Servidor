<?php

    /** MYSQL Procedimental **/

    $servername = "db";
    $user = "root";
    $pass = "test";
    $dbName = "dbname";

    //1. Crear conexión
    $con = mysqli_connect($servername, $user, $pass, $dbName);

    //2. Comprobar conexión
    if(!$con) {
        die("Fallo de conexión".mysqli_connect_error());
    }
    echo "Conexión procedimental correcta. <br>";

    //3. Crear BBDD
    $sql = "CREATE DATABASE myDBProc";
    if(mysqli_query($con, $sql)) {
        echo "BBDD creada correctamente. <br>";
    } else {
        echo "Error creando BBDD: " . mysqli_error($con);
    }

    //4. Cerrar conexión
    mysqli_close($con);
    echo "Conexión cerrada. <br>";

?>