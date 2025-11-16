<?php

    /** PDO **/

    $servername = "db";
    $user = "root";
    $pass = "test";
    $dbName = "myDBPDO";

    try {
        //1. Crear conexión
        $conPDO = new PDO("mysql:host=$servername;dbname=$dbName", $user, $pass);

        //2. Forzar excepciones
        $conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Conexión PDO correcta. <br>";

        //3. Consulta
        $sql = "UPDATE clientes SET apellido='Modificado' WHERE nombre='Sabela'";
        $stmt = $conPDO->prepare($sql);
        $stmt->execute();

        echo $stmt->rowCount() . " registros actualizados correctamente. <br>";
    } catch (PDOException $e) {
        echo "Fallo de conexión: " . $e->getMessage() . "<br>";
    }

    //6. Cerrar conexión
    $conPDO = null;
    echo "Conexión cerrada. <br>";

?>