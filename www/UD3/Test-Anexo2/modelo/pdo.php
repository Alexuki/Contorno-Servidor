<?php

define('HOST', 'db');
define('USER', 'root');
define('PASS', 'test');
define('DB', 'tareasTest');

function conectaPDO()
{
    try {
        $conexion = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USER, PASS);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;

    } catch (PDOException $e) {
        die("Error al conectar con BD (PDO)");
    }

}

function listaUsuarios()
{
    try {
        $conexion = conectaPDO();
        $sql = "SELECT * FROM usuarios";
        $stmt = $conexion->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
        $stmt->execute();
        $result = $stmt->fetchAll();
        return [true, $result];
        
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    } finally {
        $conexion = null;
    }

    
}

function listaTareasPDO($id_usuario, $estado)
{

    
}

function nuevoUsuario($nombre, $apellidos, $username, $contrasena)
{
    try {
        $conexion = conectaPDO();
        $sql = "INSERT INTO usuarios(nombre, apellidos, username, contrasena)
            VALUES (:nombre, :apellidos, :username, :contrasena)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellidos", $apellidos);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":contrasena", $contrasena);

        $stmt->execute();
        $stmt->closeCursor();
        return [true, "Usuario creado correctamente"];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    } finally {
        $conexion = null;
    }
    



}

function actualizaUsuario($id, $nombre, $apellidos, $username, $contrasena)
{

}

function borraUsuario($id)
{

}

function buscaUsuario($id)
{

    
}