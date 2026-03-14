<?php

require_once __DIR__ . "/usuario.php";

/*
* Capa de acceso de datos.
* Funciones para gestión de la conexión y operaciones en la BBDD.
*/

/**
 * Establece conexión con el contenedor de nuestra BBDD.
 */
function get_conexion()
{
    $conexion = new mysqli('db', 'root', 'test'); //contenedor de nuestra BBDD, usuario y contraseña
  
    if ($conexion->connect_errno != null) {
        die("Fallo en la conexión: " . $conexion->connect_error . "Con numero" . $conexion->connect_errno);
    }
    
    return $conexion;
}

/**
 * Selecciona una BBDD dentro de una conexión.
 */
function seleccionar_bd($conexion, $nombre_bd)
{
    return $conexion->select_db($nombre_bd);
}

/**
 * Crea una BBDD dentro de una conexión.
 */
function crear_bd($conexion, $nombre_bd) {
    $sql = "CREATE DATABASE IF NOT EXISTS $nombre_bd";
    ejecutar_consulta($conexion, $sql);
}

/**
 * Ejecutar consulta en una conexión.
 */
function ejecutar_consulta($conexion, $sql)
{
    $resultado = $conexion->query($sql);

    if ($resultado == false) {
        die($conexion->error);
    }

    return $resultado;
}

/**
 * Crea una tabla de usuarios en una conexión.
 */
function crear_tabla_usuarios($conexion)
{
    $sql = "CREATE TABLE IF NOT EXISTS usuarios(
          id INT(6) AUTO_INCREMENT PRIMARY KEY , 
          nombre VARCHAR(50) NOT NULL , 
          apellidos VARCHAR(100) NOT NULL ,
          edad INT (3) NOT NULL ,
          provincia VARCHAR(50) NOT NULL
        )";

    ejecutar_consulta($conexion, $sql);
}

function crear_tabla_productos($conexion) {
    $sql = "CREATE TABLE IF NOT EXISTS productos(
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL,
        descripcion VARCHAR(100),
        precio FLOAT NOT NULL,
        unidades INT NOT NULL,
        foto MEDIUMBLOB
    )";

    ejecutar_consulta($conexion, $sql);
}

/**
 * Devuelve la lista de usuarios de la tabla USUARIOS
 * en una conexión.
 * Esta sentencia SQL habría que limitarla en caso de tener muchos
 * registros para no agotar la memoria del servidor de la BBDD.
 */
function listar_usuarios($conexion)
{
    $sql = "SELECT *
            FROM usuarios";

    $resultado = ejecutar_consulta($conexion, $sql);
    return $resultado;
}

/**
 * Cierra la conexión al serivodr de BBDD.
 */
function cerrar_conexion($conexion)
{
    $conexion->close();
}


/* CRUD */

/**
 * Consulta preparada para crear un usuario.
 */
function dar_alta_usuario($conexion, Usuario $usuario)
{
    $sql = $conexion->prepare("INSERT INTO usuarios (nombre, apellidos, edad, provincia) VALUES (?,?,?,?)");
    $nombre = $usuario->getNombre();
    $apellidos = $usuario->getApellidos();
    $edad = $usuario->getEdad();
    $provincia = $usuario->getProvincia();
    $sql->bind_param("ssss", $nombre, $apellidos, $edad, $provincia);
    return $sql->execute() or die($conexion->error);
    // Si no se ejcuta, acabar la ejecución mostrando el error.
}

/**
 * Consulta preparada para crear un producto.
 */
function dar_alta_producto($conexion, $nombre, $descripcion, $precio, $unidades, $foto_binaria)
{
    $sql = $conexion->prepare("INSERT INTO productos (nombre, descripcion, precio, unidades, foto) VALUES (?,?,?,?,?)");

    // Para BLOB, se enlaza como tipo "b" y se envian los datos en una segunda llamada.
    $foto_placeholder = null;
    $sql->bind_param("ssdib", $nombre, $descripcion, $precio, $unidades, $foto_placeholder);
    $sql->send_long_data(4, $foto_binaria);

    return $sql->execute() or die($conexion->error);
}
 
/**
 * Obtiene un usuario de la tabla USUARIOS mediante su id.
 */
function get_usuario($conexion, $id)
{
    $sql = "SELECT *
            FROM usuarios
            WHERE id=$id";

    $resultado = ejecutar_consulta($conexion, $sql);
    return $resultado;
}

/**
 * Edita el usuario de la tabla USUARIOS mediante su id.
 */
function editar_usuario($conexion, $id, $nombre, $apellidos, $edad, $provincia)
{
    $sql = "UPDATE usuarios
            SET nombre='$nombre', apellidos='$apellidos', edad='$edad', provincia='$provincia'
            WHERE id=$id;";

    $resultado = ejecutar_consulta($conexion, $sql);
    return $resultado;
}

/**
 * Elimina un ususario de la tabla USUARIOS mediante su id.
 */
function borrar_usuario($conexion, $id)
{
    $sql = "DELETE FROM usuarios
            WHERE id=$id";

    $resultado = ejecutar_consulta($conexion, $sql);
    return $resultado;
}