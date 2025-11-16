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

    //3. Consulta
    $sql = "DELETE FROM clientes WHERE id=3";

    if(mysqli_query($con, $sql)) {
        echo "Eliminación correcta. <br>";
    } else {
         echo "Error al eliminar: " . mysqli_error($con) .  "<br>";
    }


    //6. Cerrar conexión
    mysqli_close($con);
    echo "Conexión cerrada. <br>";

?>