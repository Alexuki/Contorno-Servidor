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

        //3, 4 y 5. BBDD y tabla creados previamente

        //6. Preparar consulta
        $stmt = $conPDO->prepare("INSERT INTO clientes (nombre, apellido, email)
            VALUES (:nombre, :apellido, :email)");
        
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellido", $apellido);
        $stmt->bindParam(":email", $email);

        // Recuaperamos la información, por ejemplo de un formulario en las variables
        // y ejecutamos la sentencia preparada pasándole dichos valores
        $nombre = "Juan";
        $apellido = "Sobrino";
        $email = "juan@edu.com";
        $stmt->execute();

        $nombre = "Luisa";
        $apellido = "Álvarez";
        $email = "luisa@edu.com";
        $stmt->execute();
        

        echo "Registros insertados correctamente. <br>";

    } catch(PDOException $e) {
        echo "Fallo de conexión: " . $e->getMessage() . "<br>";
    }

    //6. Cerrar conexión
    $conPDO = null;
    echo "Conexión cerrada. <br>";

?>