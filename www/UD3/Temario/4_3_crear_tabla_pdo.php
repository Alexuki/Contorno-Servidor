<?php

    /** PDO **/

    $servername = "db";
    $user = "root";
    $pass = "test";
    $dbName = "dbname";

    try {
        //1. Crear conexión
        $conPDO = new PDO("mysql:host=$servername;dbname=$dbName", $user, $pass);

        //2. Forzar excepciones
        $conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Conexión PDO correcta. <br>";

        //3. Crear BBDD
        $sql = "CREATE DATABASE myDBPDO";
        $conPDO->exec($sql);
        echo "BBDD creada correctamente. <br>";

        //4. Crear tabla
        $conPDO->exec("USE myDBPDO");

        $sql = "CREATE TABLE clientes (
            id INT(6) AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(30) NOT NULL,
            apellido VARCHAR(30) NOT NULL,
            email VARCHAR(50) 
        )";
        $conPDO->exec($sql);
        echo "Tabla creada correctamente. <br>";

    } catch(PDOException $e) {
        echo "Fallo de conexión: " . $e->getMessage() . "<br>";
    }

    //5. Cerrar conexión
    $conPDO = null;
    echo "Conexión cerrada. <br>";

?>