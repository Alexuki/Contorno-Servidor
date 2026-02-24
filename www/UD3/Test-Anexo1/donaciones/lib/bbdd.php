<?php

function crearConexion() {
    $server = "db";
    $user = "root";
    $pass = "test";

    try {
        $conexion = new PDO("mysql:host=$server", $user, $pass);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
    
}

function cerrarConexion($conexion) {
    $conexion = null;
}

function crearDB($conexion, $dbName) {
    try {
        $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
        $conexion->exec($sql); //devuelve número de filas afectadas
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function seleccionarDB($conexion, $dbName) {
    try {
        $sql = "USE $dbName";
        $conexion->exec($sql);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function ejecutarSql($conexion, $sql) {
    try {
        return $conexion->query($sql); //Retorna un objeto PDOStatement o false si ocurre un error.
    } catch (PDOException $e) {
        return false;
    }

}
/*
 PDOStatement Representa una consulta preparada y, una vez ejecutada, el conjunto de resultados asociado.
*/

function crearTablaDonantes($conexion) {
    $sql = "CREATE TABLE IF NOT EXISTS donantes(
        id INT(6) AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(20) NOT NULL,
        apellidos VARCHAR(50) NOT NULL,
        edad INT(3) NOT NULL,
        grupo_sanguineo VARCHAR(3) NOT NULL,
        cod_postal INT(5) NOT NULL,
        movil INT(9) NOT NULL
    )";

    ejecutarSql($conexion, $sql);
}

function crearTablaHistorico($conexion) {
    $sql = "CREATE TABLE IF NOT EXISTS historico(
        donanteId INT(6) NOT NULL,
        fecha DATE NOT NULL,
        fecha_proxima DATE NOT NULL,
        PRIMARY KEY (donanteId, fecha),
        FOREIGN KEY (donanteId) REFERENCES donantes(id) ON DELETE CASCADE
    )";

    ejecutarSql($conexion, $sql);
}

function crearTablaAdministradores($conexion) {
    $sql = "CREATE TABLE IF NOT EXISTS administradores(
        nombre VARCHAR(50) PRIMARY KEY,
        contraseña VARCHAR(200) NOT NULL
    )";

    ejecutarSql($conexion, $sql);
}

function crearDonante($conexion, $nombre, $apellidos, $edad, $grupoSanguineo, $codPostal, $movil) {
    try {
        $sql = "INSERT INTO donantes (nombre, apellidos, edad, grupo_sanguineo, cod_postal, movil ) VALUES (:nombre, :apellidos, :edad, :grupoSanguineo, :codPostal, :movil)";

        $stmt = $conexion->prepare($sql);

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellidos", $apellidos);
        $stmt->bindParam(":edad", $edad);
        $stmt->bindParam(":grupoSanguineo", $grupoSanguineo);
        $stmt->bindParam(":codPostal", $codPostal);
        $stmt->bindParam(":movil", $movil);

        $stmt->execute(); //Devuelve true o false para indicar si la consulta tuvo éxito o no.
        $stmt-> closeCursor();

        return [true, "Donante creado correctamente"];

    } catch (PDOException $e) {
        return [false, "Donante no creado en BBDD"];
    }
}

function listarDonantes($conexion) {
    $sql = "SELECT * FROM donantes";
    return ejecutarSql($conexion, $sql); //Devuelve PDOStatement
}

function eliminarDonante($conexion, $id) {
    try {
        $sql = "DELETE FROM donantes WHERE id = :id";
        $stmnt = $conexion->prepare($sql);
        $stmnt->bindParam(":id", $id);
        $stmnt->execute();
        $stmnt->closeCursor();

        return [true, "Donante con id $id borrado"];

    } catch (PDOException $e) {
        return [false, "No se puedo borrar el donante con id: $id"];
    }
}

function getUltimaDonacion($conexion, $id) {
    try {
        $sql = "SELECT * FROM historico
                WHERE donanteId = :donanteId
                ORDER BY fecha_proxima DESC
                LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":donanteId", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetch();
        $stmt->closeCursor();

        if($resultado) {
            return [true, $resultado];
        } else {
            return [false, "No existen donaciones registradas"];
        }
    } catch (PDOException $e) {
        return [false, "Error al recuperar las donaciones de usuario"];
    }
}

function crearDonacion($conexion, $id, $fecha) {
    try {
        $fechaProxima = date("Y-m-d",strtotime($fecha . "+4 month")); //$fecha viene en formato Y-m-d. Se transfomra en timestamp para el cálculo y vuelve a ponerse en ese formato al final
        $sql = "INSERT INTO historico (donanteId, fecha, fecha_proxima)
        VALUES (:id, :fecha, :fechaProxima)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":fechaProxima", $fechaProxima);
        $stmt->execute();
        $stmt->closeCursor();
        return [true, "Donación añadida correctamente"];

    } catch (PDOException $e) {
        return [false, "Error al crear la donación"];
    }
}


function listaDonaciones($conexion, $donanteId) {
    try {
        $sql = "SELECT d.nombre, d.apellidos, h.fecha, h.fecha_proxima
                FROM donantes d
                LEFT JOIN historico h
                ON d.id = h.donanteId AND d.id = $donanteId
                WHERE d.id = $donanteId
                ORDER BY h.fecha DESC
                ";
        $result = ejecutarSql($conexion, $sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        return [true, $result];

    } catch (PDOException $e) {
        return [false, "ERROR al recuperar las donaciones del donante con id: $donanteId"];
    }
    
}

function buscaDonante($conexion, $codPostal, $grupoSanguineo) {
    $sql = "SELECT DISTINCT d.* FROM donantes d
            LEFT JOIN historico h ON d.id = h.donanteId
            WHERE 1=1";
    
    if ($codPostal !== null) {
        $sql .= " AND d.cod_postal = :codPostal";
    }
    
    if ($grupoSanguineo !== null) {
        $sql .= " AND d.grupo_sanguineo = :grupoSanguineo";
    }

    $hoy = date("Y-m-d");
    $sql .= " AND (h.fecha_proxima >= :hoy OR h.donanteId IS NULL)";
    
    try {
        $stmt = $conexion->prepare($sql);
        
        $stmt->bindParam(":hoy", $hoy);
        
        if ($codPostal !== null) {
            $stmt->bindParam(":codPostal", $codPostal);
        }
        
        if ($grupoSanguineo !== null) {
            $stmt->bindParam(":grupoSanguineo", $grupoSanguineo);
        }
        
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return [true, $stmt];
        
    } catch (PDOException $e) {
        return [false, "Error al buscar donantes"];
    }
}