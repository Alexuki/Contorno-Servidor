<?php

require_once(__DIR__ . '/mysqli.php');
require_once(__DIR__ . '/FicherosDBInt.php');
require_once(__DIR__ . '/Fichero.php');
require_once(__DIR__ . '/DatabaseException.php');

class FicherosDBImp implements FicherosDBInt
{
    public function listaFicheros($id_tarea): array
    {
        $sql = 'SELECT id, nombre, file, descripcion, id_tarea FROM ficheros WHERE id_tarea = ? ORDER BY id DESC';
        $conexion = null;
        try {
            $conexion = conectaTareas();
            if ($conexion->connect_error) {
                throw new DatabaseException($conexion->connect_error, __METHOD__, $sql);
            }

            $stmt = $conexion->prepare($sql);
            if ($stmt === false) {
                throw new DatabaseException($conexion->error, __METHOD__, $sql);
            }

            $id_tarea = (int) $id_tarea;
            $stmt->bind_param('i', $id_tarea);
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

            return $ficheros;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw $e;
            }
            throw new DatabaseException($e->getMessage(), __METHOD__, $sql, 0, $e);
        } finally {
            cerrarConexion($conexion);
        }
    }

    public function buscaFichero($id): Fichero
    {
        $sql = 'SELECT id, nombre, file, descripcion, id_tarea FROM ficheros WHERE id = ?';
        $conexion = null;
        try {
            $conexion = conectaTareas();
            if ($conexion->connect_error) {
                throw new DatabaseException($conexion->connect_error, __METHOD__, $sql);
            }

            $stmt = $conexion->prepare($sql);
            if ($stmt === false) {
                throw new DatabaseException($conexion->error, __METHOD__, $sql);
            }

            $id = (int) $id;
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows !== 1) {
                throw new DatabaseException('No existe el fichero indicado.', __METHOD__, $sql);
            }

            $row = $result->fetch_assoc();
            return new Fichero(
                (int) $row['id'],
                (string) $row['nombre'],
                (string) $row['file'],
                (string) $row['descripcion'],
                (int) $row['id_tarea']
            );
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw $e;
            }
            throw new DatabaseException($e->getMessage(), __METHOD__, $sql, 0, $e);
        } finally {
            cerrarConexion($conexion);
        }
    }

    public function borraFichero($id): bool
    {
        $sql = 'DELETE FROM ficheros WHERE id = ?';
        $conexion = null;
        try {
            $conexion = conectaTareas();
            if ($conexion->connect_error) {
                throw new DatabaseException($conexion->connect_error, __METHOD__, $sql);
            }

            $stmt = $conexion->prepare($sql);
            if ($stmt === false) {
                throw new DatabaseException($conexion->error, __METHOD__, $sql);
            }

            $id = (int) $id;
            $stmt->bind_param('i', $id);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw $e;
            }
            throw new DatabaseException($e->getMessage(), __METHOD__, $sql, 0, $e);
        } finally {
            cerrarConexion($conexion);
        }
    }

    public function nuevoFichero($fichero): bool
    {
        $sql = 'INSERT INTO ficheros (nombre, file, descripcion, id_tarea) VALUES (?,?,?,?)';
        $conexion = null;
        try {
            if (!($fichero instanceof Fichero)) {
                throw new DatabaseException('El parametro recibido no es un objeto Fichero.', __METHOD__, $sql);
            }

            $conexion = conectaTareas();
            if ($conexion->connect_error) {
                throw new DatabaseException($conexion->connect_error, __METHOD__, $sql);
            }

            $stmt = $conexion->prepare($sql);
            if ($stmt === false) {
                throw new DatabaseException($conexion->error, __METHOD__, $sql);
            }

            $nombre = $fichero->getNombre();
            $file = $fichero->getFile();
            $descripcion = $fichero->getDescripcion();
            $id_tarea = $fichero->getTarea();
            $stmt->bind_param('sssi', $nombre, $file, $descripcion, $id_tarea);
            $stmt->execute();

            return $stmt->affected_rows > 0;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw $e;
            }
            throw new DatabaseException($e->getMessage(), __METHOD__, $sql, 0, $e);
        } finally {
            cerrarConexion($conexion);
        }
    }
}
