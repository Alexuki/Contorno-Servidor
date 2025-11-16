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

    //4. Crear tabla
    // Seleccionar/cambiar base de datos
    $conexion->select_db("myDBoo");
    echo "Cambio de BBDD. <br>";

    //Crear tabla
    $sql = "CREATE TABLE clientes (
            id INT(6) AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(30) NOT NULL,
            apellido VARCHAR(30) NOT NULL,
            email VARCHAR(50) 
        )";
    
    if($conexion->query($sql)) {
        echo "Tabla creada correctamente. <br>";
    } else {
        echo "Error creando la tabla." .  $conexion->error . "<br>";

    }



    //5. Cerrar conexión
    $conexion->close();
    echo "Conexión cerrada. <br>";

?>