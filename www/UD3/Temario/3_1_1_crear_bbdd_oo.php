<?php

    /** MYSQL Orientado a Objetos **/

    $servername = "db";
    $user = "root";
    $pass = "test";
    $dbName = "dbname";


    //1. Crear conexión.
    @$conexion = new mysqli($servername, $user, $pass, $dbName);

    //2. Comprobar la conexión
    $errorNum = $conexion->connect_errno;
    $errorMensaje = $conexion->connect_error;
    if($errorNum != null) {
        die("Fallo de conexión: " . $errorMensaje . " Con número: " . $errorNum);
    }
    echo "Conexión correcta <br>";

    //3. Crear BBDD
    $sql = "CREATE DATABASE myDBoo";
    if($conexion->query($sql)) {
        echo "BBDD creada correctamente <br>";
    } else {
        echo "Error creando la BBDD: " . $conexion->error; // Muestra el último error generado por $conexion
    }

    //4. Cerrar conexión
    $conexion->close();
    echo "Conexión cerrada. <br>";

?>