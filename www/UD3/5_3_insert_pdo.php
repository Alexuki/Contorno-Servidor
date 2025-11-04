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

        //3 y 4. BBDD y tabla creadas previamente

        //5. Crear registro
        /* $sql = "INSERT INTO clientes (nombre, apellido, email)
                VALUES ('Alejandro', 'Martínez', 'alex@sanclemente.net')";
        $conPDO->exec($sql);

        $ultimo_id = $conPDO->lastInsertId();
        echo "Registro insertado correctamente con id $ultimo_id. <br>"; */

        //5. Crear varios registros. Emplea transacciones

        //INICIO TRANSACCIÓN
        $conPDO->beginTransaction();
        
        //SENTENCIAS. No se ejecutan hasta hacer el commit
        $sql = "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Alejandro', 'Martínez', 'alex@sanclemente.net')";
        $conPDO->exec($sql);

        $sql = "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Iván', 'Gómez', 'ivan@sanclemente.net')";
        $conPDO->exec($sql);

        $sql = "INSERT INTO clientes (nombre, apellido, email)
            VALUES ('Sabela', 'Sobrino', 'sabela@sanclemente.net')";
        $conPDO->exec($sql);

        //COMMIT
        $conPDO->commit();

        echo "Registros insertados correctamente. <br>";

    } catch(PDOException $e) {
        //ROLLBACK. Si hay un error en alguna sentencia, no ejecutar ninguna
        $conPDO->rollBack();
        echo "Fallo de conexión: " . $e->getMessage() . "<br>";
    }

    //6. Cerrar conexión
    $conPDO = null;
    echo "Conexión cerrada. <br>";

?>