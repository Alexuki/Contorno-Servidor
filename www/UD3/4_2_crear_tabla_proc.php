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

    //4. Crear tabla
    mysqli_select_db($con, "myDBProc");
    $sql = "CREATE TABLE clientes (
            id INT(6) AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(30) NOT NULL,
            apellido VARCHAR(30) NOT NULL,
            email VARCHAR(50) 
        )";
    if(mysqli_query($con, $sql)) {
        echo "Tabla creada correctamente <br>";
    } else {
        echo "Error creando la tabla. " . mysqli_error($con) . "<br>";
    }

    //5. Cerrar conexión
    mysqli_close($con);
    echo "Conexión cerrada. <br>";

?>