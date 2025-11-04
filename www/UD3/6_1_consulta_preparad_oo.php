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

    //3, 4 y 5. BBDD, y tabla creados previamente

    //6. Consultas preparadas

    //6.1 Preparar la consulta
    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, apellido, email)
            VALUES (?, ?, ?)");
    // Indicar los tipos de parámetros y las variables que vamos a utilizar
    // Los parámetros "?" van a ser sustituidos en orden por los indicados
    // Los indicados son variables que pueden definirse después
    // Puede hacerse las veces que se quiera
    $stmt->bind_param("sss", $nombre, $apellido, $email);

    // Si obtenemos datos de un formulario podemos tener un bucle for que ejecute esto

    $nombre = "Alejandro";
    $apellido = "García";
    $email = "alejandro@edu.com";
    $stmt->execute(); //Coge la parte preparada y sustituye los parámetros

    $nombre = "María";
    $apellido = "Pérez";
    $email = "maria@edu.com";
    $stmt->execute();

    echo "Los registros se crearon correctamente. <br>";

    $stmt->close(); // IMPORTANTE cerrar esta variable
    

    //6. Cerrar conexión
    $conexion->close();
    echo "Conexión cerrada. <br>";

?>