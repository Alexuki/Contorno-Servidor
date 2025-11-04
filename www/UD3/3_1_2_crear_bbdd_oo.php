<?php

    /** MYSQL Orientado a Objetos **/
    // Crear BBDD llamada myDBoo

    try {
        //1. Crear conexión sin indicar BD
        $conexion = new mysqli("db", "root", "test");
        echo "Conexión correcta";

        //1b. Crear base de datos
        $sql = "CREATE DATABASE myDBoo";
        if($conexion->query($sql)) {
            echo "BBDD creada correctamente <br>";
        }
        else {
            echo "Error creando la BBDD: " . $conexion->error . "<br>";
        }

    } 
    catch (mysqli_sql_exception $e) {
        //2. Gestionar el error si hubiera
        echo "Error en la conexión: " . $e->getMessage() . "<br>";
    }
    finally {
        //3. Cerrar la conexión si se estableció
        if (isset($conexion) && $conexion->connect_errno === 0) {
            $conexion->close();
            echo "Conexión cerrada.";
        }
    }

?>