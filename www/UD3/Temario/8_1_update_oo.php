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

    //3. Consulta
    $sql = "UPDATE clientes SET apellido='Modificado' WHERE nombre='Sabela'";

    if($conexion->query($sql)) {
        echo "Actualización correcta. <br>";
    } else {
        echo "Error al actualizar: " . $conexion->error .  "<br>";
    }
    

    //6. Cerrar conexión
    $conexion->close();
    echo "Conexión cerrada. <br>";

?>