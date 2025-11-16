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

    //3 y 4. BBDD y tabla creadas previamente

    //5. Crear registro
    /* $sql = "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Alejandro', 'Martínez', 'alex@sanclemente.net')";

    if(mysqli_query($con, $sql)) {
        $ultimo_id = mysqli_insert_id($con);
        echo "Registro creado correctamente con id $ultimo_id. <br>";
    } else {
        echo "Error al crear el registro: " .  mysqli_error($con) . "<br>";
    } */

    //5. Crear varios registros
    $sql = "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Alejandro', 'Martínez', 'alex@sanclemente.net');";
    $sql .= "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Iván', 'Gómez', 'ivan@sanclemente.net');";
    $sql .= "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Sabela', 'Sobrino', 'sabela@sanclemente.net')";

    if(mysqli_multi_query($con, $sql)) {
        echo "Registro creados correctamente. <br>";
    } else {
        echo "Error al crear los registros: " .  mysqli_error($con) . "<br>";
    }

    //6. Cerrar conexión
    mysqli_close($con);
    echo "Conexión cerrada. <br>";

?>