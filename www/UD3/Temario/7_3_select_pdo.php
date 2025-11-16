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
        $sql = "SELECT id, nombre, apellido FROM clientes";
        $stmt = $conPDO->prepare($sql);
        $stmt->execute();

        // Recuperar resultado y guardar como array asociativo
        // Indicar el modo de recorrer la matriz
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultados = $stmt->fetchAll();

        //var_dump($resultados);

        foreach($resultados as $k=>$v) {
            //echo $k . "<br>"; // 0, 1, 2...
            echo $v["id"] . " - " . $v["nombre"] . " " . $v["apellido"] . "<br>";
        }

        } catch(PDOException $e) {
            echo "Fallo de conexión: " . $e->getMessage() . "<br>";
        }

    //6. Cerrar conexión
    $conPDO = null;
    echo "Conexión cerrada. <br>";

?>