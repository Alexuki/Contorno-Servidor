<?php

    function getConexion($server, $user, $pass ) {
        $conexion = new mysqli($server, $user, $pass);
        $error = $conexion->connect_error;
        if($error) {
            die("Fallo de conexión" . $error);
        }
        return $conexion;
    }

    function getConexionTienda() {
        $db = "db";
        $user = "root";
        $pass = "test";

        $conexion = getConexion($db, $user, $pass);
        return $conexion;
    }


    function cerrarConexion($conexion) {
        $conexion->close();
    }

    
    function crearBD($conexion, $nombre) {
        $sql = "CREATE DATABASE IF NOT EXISTS $nombre";
        $result = ejecutarSql($conexion, $sql);
        return $result;
    }


    function seleccionarBD($conexion, $nombreBD) {
        return $conexion->select_db($nombreBD); //Devuelve true o false
    }


    /*
    $result al ejecutar una query puede ser true o un conjunto de resultados
    en caso exitoso (mysqli_result object), y false en caso de fallo.
     */
    function ejecutarSql($conexion, $sql) {
        try {
            $result = $conexion->query($sql);
            
            if ($result === false) {
                return [false, $conexion->error];
            }
            
            return [true, $result];
            
        } catch (mysqli_sql_exception $e) {
            return [false, $e->getMessage()];
        }
    }

    function crearTablaUsuarios($conexion) {
        $sql = "CREATE TABLE IF NOT EXISTS usuarios(
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(50) NOT NULL,
            apellidos VARCHAR(100) NOT NULL,
            edad INT(3) NOT NULL,
            provincia VARCHAR(50) NOT NULL
        )";
        return ejecutarSql($conexion, $sql);
    }

    function crearUsuario($conexion, $nombre, $apellidos, $edad, $provincia) {
        $sql = "INSERT INTO usuarios (nombre, apellidos, edad, provincia)
                VALUES ('$nombre', '$apellidos', $edad, '$provincia')";
        return ejecutarSql($conexion, $sql);   
    }

    function crearUsuarioPreparada($conexion, $nombre, $apellidos, $edad, $provincia) {
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, apellidos, edad, provincia) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $nombre, $apellidos, $edad, $provincia );
        $result = $stmt->execute();
        if(!$result) {
            die("Error en la cración de usuario con consulta preparada");
        }
        $stmt->close();
        return $result;
        /*
        prepare: Devuelve un objeto de sentencia (mysqli_stmt) o false en caso de error.
        bind_param: Devuelve true en caso de éxito o false si se produce un error.
        execute: Devuelve true en caso de éxito o false si se produce un error.
        mysqli_stmt_fetch: Lee los resultados de una consulta MySQL preparada en variables vinculadas
        */
    }


    function getUsuarios($conexion) {
        $sql = "SELECT * FROM usuarios";
        return ejecutarSql($conexion, $sql);
    }

    function getUsuarioPorId($conexion, $id) {
        $sql = "SELECT * FROM usuarios
                WHERE id = $id";
        return ejecutarSql($conexion, $sql);
    }

    function editarUsuario($conexion, $id, $nombre, $apellidos, $edad, $provincia) {
        $sql = "UPDATE usuarios
                SET nombre = '$nombre', apellidos = '$apellidos', edad = '$edad', provincia = '$provincia'
                WHERE id = $id";
        return ejecutarSql($conexion, $sql);
    }

    function eliminarUsuario($conexion, $id) {
        $sql = "DELETE FROM usuarios WHERE id = $id";
        return ejecutarSql($conexion, $sql);
    }

