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
    $sql = "SELECT id, nombre, apellido FROM clientes";
    $resultados = $conexion->query($sql);

    if($resultados->num_rows > 0) {
        // Colocar resultados en matriz asociativa con fetch_assoc()
        // que devuelve una fila del conjunto de resultados cada vez que se invoca
        while($row = $resultados->fetch_assoc()) {
            echo $row["id"] . " - " . $row["nombre"] . " " . $row["apellido"] . "<br>";
        }
    } else {
        echo "No hay resultados <br>";
    }
    

    //6. Cerrar conexión
    $conexion->close();
    echo "Conexión cerrada. <br>";

?>