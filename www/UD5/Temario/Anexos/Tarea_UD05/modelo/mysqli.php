<?php

require_once(__DIR__ . '/Usuario.php');
require_once(__DIR__ . '/Tarea.php');
require_once(__DIR__ . '/Fichero.php');

function envValue($key, $default = null)
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }
    return $value;
}

function conecta($host, $user, $pass, $db = null)
{
    return new mysqli($host, $user, $pass, $db);
}

function conectaTareas()
{
    return conecta(
        envValue('DATABASE_HOST', 'db'),
        envValue('DATABASE_USER', 'root'),
        envValue('DATABASE_PASSWORD', 'test'),
        envValue('DATABASE_NAME', 'tareas')
    );
}

function cerrarConexion($conexion)
{
    if (isset($conexion) && $conexion instanceof mysqli && $conexion->connect_errno === 0) {
        $conexion->close();
    }
}

function creaDB()
{
    $conexion = null;
    try {
        $dbName = envValue('DATABASE_NAME', 'tareas');
        $conexion = conecta(
            envValue('DATABASE_HOST', 'db'),
            envValue('DATABASE_USER', 'root'),
            envValue('DATABASE_PASSWORD', 'test')
        );

        if ($conexion->connect_error) {
            return [false, $conexion->error];
        }

        $stmt = $conexion->prepare('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?');
        $stmt->bind_param('s', $dbName);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado && $resultado->num_rows > 0) {
            return [false, 'La base de datos "' . $dbName . '" ya existia.'];
        }

        $sql = 'CREATE DATABASE IF NOT EXISTS `' . $dbName . '`';
        if ($conexion->query($sql)) {
            return [true, 'Base de datos "' . $dbName . '" creada correctamente'];
        }

        return [false, 'No se pudo crear la base de datos "' . $dbName . '".'];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function createTablaUsuarios()
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        if ($conexion->connect_error) {
            return [false, $conexion->error];
        }

        $sqlCheck = "SHOW TABLES LIKE 'usuarios'";
        $resultado = $conexion->query($sqlCheck);
        if ($resultado && $resultado->num_rows > 0) {
            // Migracion de esquema legacy: anadir rol y ampliar contrasena para hashes modernos.
            $cols = [];
            $resCols = $conexion->query('SHOW COLUMNS FROM usuarios');
            while ($row = $resCols->fetch_assoc()) {
                $cols[$row['Field']] = $row;
            }

            $mensajes = [];
            if (!isset($cols['rol'])) {
                $conexion->query('ALTER TABLE usuarios ADD COLUMN rol TINYINT NOT NULL DEFAULT 0');
                $mensajes[] = 'Columna rol anadida';
            }

            if (isset($cols['contrasena'])) {
                $conexion->query('ALTER TABLE usuarios MODIFY contrasena VARCHAR(255) NOT NULL');
                $mensajes[] = 'Columna contrasena actualizada';
            }

            if (count($mensajes) > 0) {
                return [true, 'Tabla "usuarios" actualizada: ' . implode(', ', $mensajes) . '.'];
            }

            return [false, 'La tabla "usuarios" ya existia.'];
        }

        $sql = 'CREATE TABLE `usuarios` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `nombre` VARCHAR(50) NOT NULL,
            `apellidos` VARCHAR(100) NOT NULL,
            `contrasena` VARCHAR(255) NOT NULL,
            `rol` TINYINT NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        )';

        if ($conexion->query($sql)) {
            return [true, 'Tabla "usuarios" creada correctamente'];
        }

        return [false, 'No se pudo crear la tabla "usuarios".'];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function createTablaTareas()
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        if ($conexion->connect_error) {
            return [false, $conexion->error];
        }

        $sqlCheck = "SHOW TABLES LIKE 'tareas'";
        $resultado = $conexion->query($sqlCheck);
        if ($resultado && $resultado->num_rows > 0) {
            return [false, 'La tabla "tareas" ya existia.'];
        }

        $sql = 'CREATE TABLE `tareas` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `titulo` VARCHAR(50) NOT NULL,
            `descripcion` VARCHAR(250) NOT NULL,
            `estado` VARCHAR(50) NOT NULL,
            `id_usuario` INT NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`id_usuario`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
        )';

        if ($conexion->query($sql)) {
            return [true, 'Tabla "tareas" creada correctamente'];
        }

        return [false, 'No se pudo crear la tabla "tareas".'];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function createTablaFicheros()
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        if ($conexion->connect_error) {
            return [false, $conexion->error];
        }

        $sqlCheck = "SHOW TABLES LIKE 'ficheros'";
        $resultado = $conexion->query($sqlCheck);
        if ($resultado && $resultado->num_rows > 0) {
            return [false, 'La tabla "ficheros" ya existia.'];
        }

        $sql = 'CREATE TABLE `ficheros` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `nombre` VARCHAR(100) NOT NULL,
            `file` VARCHAR(250) NOT NULL,
            `descripcion` VARCHAR(250) NOT NULL,
            `id_tarea` INT NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`id_tarea`) REFERENCES `tareas`(`id`) ON DELETE CASCADE
        )';

        if ($conexion->query($sql)) {
            return [true, 'Tabla "ficheros" creada correctamente'];
        }

        return [false, 'No se pudo crear la tabla "ficheros".'];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function listaTareas()
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        if ($conexion->connect_error) {
            return [false, $conexion->error];
        }

        $sql = 'SELECT id, titulo, descripcion, estado, id_usuario FROM tareas ORDER BY id DESC';
        $resultados = $conexion->query($sql);
        $tareas = [];
        while ($row = $resultados->fetch_assoc()) {
            $tareas[] = new Tarea(
                (int) $row['id'],
                (string) $row['titulo'],
                (string) $row['descripcion'],
                (string) $row['estado'],
                (int) $row['id_usuario']
            );
        }
        return [true, $tareas];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function listaTareasPorUsuario($idUsuario)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        $sql = 'SELECT id, titulo, descripcion, estado, id_usuario FROM tareas WHERE id_usuario = ? ORDER BY id DESC';
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();
        $resultados = $stmt->get_result();

        $tareas = [];
        while ($row = $resultados->fetch_assoc()) {
            $tareas[] = new Tarea(
                (int) $row['id'],
                (string) $row['titulo'],
                (string) $row['descripcion'],
                (string) $row['estado'],
                (int) $row['id_usuario']
            );
        }

        return [true, $tareas];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function nuevaTarea(Tarea $tarea)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        if ($conexion->connect_error) {
            return [false, $conexion->error];
        }

        $stmt = $conexion->prepare('INSERT INTO tareas (titulo, descripcion, estado, id_usuario) VALUES (?,?,?,?)');
        $titulo = $tarea->getTitulo();
        $descripcion = $tarea->getDescripcion();
        $estado = $tarea->getEstado();
        $usuario = $tarea->getUsuario();
        $stmt->bind_param('sssi', $titulo, $descripcion, $estado, $usuario);
        $stmt->execute();

        return [true, 'Tarea creada correctamente.'];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function actualizaTarea(Tarea $tarea)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        if ($conexion->connect_error) {
            return [false, $conexion->error];
        }

        $sql = 'UPDATE tareas SET titulo = ?, descripcion = ?, estado = ?, id_usuario = ? WHERE id = ?';
        $stmt = $conexion->prepare($sql);
        $titulo = $tarea->getTitulo();
        $descripcion = $tarea->getDescripcion();
        $estado = $tarea->getEstado();
        $usuario = $tarea->getUsuario();
        $id = $tarea->getId();
        $stmt->bind_param('sssii', $titulo, $descripcion, $estado, $usuario, $id);
        $stmt->execute();

        return [true, 'Tarea actualizada correctamente.'];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function borraTarea(Tarea $tarea)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        if ($conexion->connect_error) {
            return [false, $conexion->error];
        }

        $stmt = $conexion->prepare('DELETE FROM tareas WHERE id = ?');
        $id = $tarea->getId();
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return [true, 'Tarea borrada correctamente.'];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function buscaTarea($id)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        $stmt = $conexion->prepare('SELECT * FROM tareas WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultados = $stmt->get_result();
        if ($resultados->num_rows === 1) {
            $row = $resultados->fetch_assoc();
            return new Tarea(
                (int) $row['id'],
                (string) $row['titulo'],
                (string) $row['descripcion'],
                (string) $row['estado'],
                (int) $row['id_usuario']
            );
        }
        return null;
    } catch (mysqli_sql_exception $e) {
        return null;
    } finally {
        cerrarConexion($conexion);
    }
}

function buscaUsuarioMysqli($id)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        $stmt = $conexion->prepare('SELECT * FROM usuarios WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultados = $stmt->get_result();
        if ($resultados->num_rows === 1) {
            $row = $resultados->fetch_assoc();
            return new Usuario(
                (int) $row['id'],
                (string) $row['username'],
                (string) $row['nombre'],
                (string) $row['apellidos'],
                (string) $row['contrasena'],
                (int) $row['rol']
            );
        }
        return null;
    } catch (mysqli_sql_exception $e) {
        return null;
    } finally {
        cerrarConexion($conexion);
    }
}

function listaFicherosDeTarea($idTarea)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        $stmt = $conexion->prepare('SELECT * FROM ficheros WHERE id_tarea = ? ORDER BY id DESC');
        $stmt->bind_param('i', $idTarea);
        $stmt->execute();
        $result = $stmt->get_result();

        $ficheros = [];
        while ($row = $result->fetch_assoc()) {
            $ficheros[] = new Fichero(
                (int) $row['id'],
                (string) $row['nombre'],
                (string) $row['file'],
                (string) $row['descripcion'],
                (int) $row['id_tarea']
            );
        }

        return [true, $ficheros];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function insertaFicheroTarea(Fichero $fichero)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        $stmt = $conexion->prepare('INSERT INTO ficheros (nombre, file, descripcion, id_tarea) VALUES (?,?,?,?)');
        $nombre = $fichero->getNombre();
        $rutaRelativa = $fichero->getFile();
        $descripcion = $fichero->getDescripcion();
        $idTarea = $fichero->getTarea();
        $stmt->bind_param('sssi', $nombre, $rutaRelativa, $descripcion, $idTarea);
        $stmt->execute();
        return [true, 'Fichero subido correctamente.'];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}

function buscaFichero($idFichero)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        $stmt = $conexion->prepare('SELECT f.* FROM ficheros f WHERE f.id = ?');
        $stmt->bind_param('i', $idFichero);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            return new Fichero(
                (int) $row['id'],
                (string) $row['nombre'],
                (string) $row['file'],
                (string) $row['descripcion'],
                (int) $row['id_tarea']
            );
        }
        return null;
    } catch (mysqli_sql_exception $e) {
        return null;
    } finally {
        cerrarConexion($conexion);
    }
}

function borraFichero(Fichero $fichero)
{
    $conexion = null;
    try {
        $conexion = conectaTareas();
        $stmt = $conexion->prepare('DELETE FROM ficheros WHERE id = ?');
        $idFichero = $fichero->getId();
        $stmt->bind_param('i', $idFichero);
        $stmt->execute();
        return [true, 'Fichero borrado correctamente.'];
    } catch (mysqli_sql_exception $e) {
        return [false, $e->getMessage()];
    } finally {
        cerrarConexion($conexion);
    }
}
