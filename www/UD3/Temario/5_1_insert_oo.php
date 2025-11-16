<?php

    /** MYSQL Orientado a Objetos **/

    $servername = "db";
    $user = "root";
    $pass = "test";
    $dbName = "myDBoo";


    //1. Crear conexión.
    @$conexion = new mysqli($servername, $user, $pass, $dbName);

    //2. Comprobar la conexión
    $errorNum = $conexion->connect_errno;
    $errorMensaje = $conexion->connect_error;
    if($errorNum != null) {
        die("Fallo de conexión: " . $errorMensaje . " Con número: " . $errorNum);
    }
    echo "Conexión correcta. <br>";

    //3 y 4. BBDD y tabla ya creadas previamente

    //5. Insertar datos con query simple
    /* $sql = "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Alejandro', 'Martínez', 'alex@sanclemente.net')";


    if($conexion->query($sql)) {
        $ultimo_id = $conexion->insert_id; //id de último registro creado/actualizado
        echo "Registro creado correctamente con id $ultimo_id. <br>";
    } else {
        echo "Error al crear el registro: " .  $conexion->error . "<br>";
    } */

    //5b. Insertar datos con query múltiple
    $sql = "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Alejandro', 'Martínez', 'alex@sanclemente.net');";
    $sql .= "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Iván', 'Gómez', 'ivan@sanclemente.net');";
    $sql .= "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Sabela', 'Sobrino', 'sabela@sanclemente.net')";


    if($conexion->multi_query($sql)) {
        $ultimo_id = $conexion->insert_id; //devuelve el del primer registro insertado en la multi_query
        echo "Registro creado correctamente con id $ultimo_id. <br>";
    } else {
        echo "Error al crear el registro: " .  $conexion->error . "<br>";
    }

    //6. Cerrar conexión
    $conexion->close();
    echo "Conexión cerrada. <br>";

?>