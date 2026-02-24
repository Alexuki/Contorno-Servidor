<?php

define('HOST', 'db');
define('USER', 'root');
define('PASS', 'test');
define('DB', 'tareasTest');

function conecta($host, $user, $pass, $db)
{
    try {
        $conexion = new mysqli($host, $user, $pass, $db);
        return $conexion;
    } catch (mysqli_sql_exception $e) {
        die ("Error al conectar a la BBDD");
    }
}

function conectaTareas()
{
    return conecta(HOST, USER, PASS, DB);
}

function cerrarConexion($conexion)
{
    if(isset($conexion) && !$conexion->connect_error) {
        $conexion->close();
    }
}

function creaDB()
{
    try {
        $conexion = conecta(HOST, USER, PASS, null);

        $sqlCheck = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB . "'";
        $resultado = $conexion->query($sqlCheck);

        if($resultado && $resultado->num_rows > 0) {
            return [false, "DB 'tareas' ya existe"];
        } else {
            $sql = "CREATE DATABASE IF NOT EXISTS " . DB;
            $resultado = $conexion->query($sql);
            if ($resultado) {
                return [true, "BD " . DB . " creada"];
            } else {
                return [false, "No se pudo crear la BD " . DB];
            }
        }  
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }    
}

function createTablaUsuarios()
{
    $sql = "CREATE TABLE IF NOT EXISTS usuarios(
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50),
            nombre VARCHAR(50),
            apellidos VARCHAR(100),
            contrasena VARCHAR(100)
    )";
    try {
        $conexion = conectaTareas();

        $sqlCheck = "SHOW TABLES LIKE 'usuarios'";
        $resultado = $conexion->query($sqlCheck);

        if ($resultado && $resultado->num_rows > 0) {
            return [false, "Tabla usuarios ya existe"];
        } else {
            $resultado = $conexion->query($sql);
            if ($resultado) {
                return [true, "TABLA 'usuarios' creada"];
            } else {
                return [false, "No se pudo crear la TABLA 'usuarios'"];
            }
        }
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function createTablaTareas()
{
    $sql = "CREATE TABLE IF NOT EXISTS tareas(
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(50),
            descripcion VARCHAR(250),
            estado VARCHAR(50),
            id_usuario INT,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
    )";
    try {
        $conexion = conectaTareas();

        $sqlCheck = "SHOW TABLES LIKE 'usuarios'";
        $resultado = $conexion->query($sqlCheck);

        if ($resultado && $resultado->num_rows > 0) {
            return [false, "Tabla tareas ya existe"];
        } else {
            $resultado = $conexion->query($sql);
            if ($resultado) {
                return [true, "TABLA 'tareas' creada"];
            } else {
                return [false, "No se pudo crear la TABLA 'tareas'"];
            }
        }
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function listaTareas()
{
    
}

function nuevaTarea($titulo, $descripcion, $estado, $usuario)
{
    
}

function actualizaTarea($id, $titulo, $descripcion, $estado, $usuario)
{
    
}

function borraTarea($id)
{
   
}

function buscaTarea($id)
{
    
}

function buscaUsuarioMysqli($id)
{
    
}