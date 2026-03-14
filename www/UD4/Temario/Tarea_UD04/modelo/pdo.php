<?php

function envValuePDO($key, $default = null)
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }
    return $value;
}

function conectaPDO()
{
    $host = envValuePDO('DATABASE_HOST', 'db');
    $user = envValuePDO('DATABASE_USER', 'root');
    $pass = envValuePDO('DATABASE_PASSWORD', 'test');
    $db = envValuePDO('DATABASE_NAME', 'tareas');

    $conPDO = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $conPDO;
}

function listaUsuarios()
{
    $con = null;
    try {
        $con = conectaPDO();
        $stmt = $con->prepare('SELECT id, username, nombre, apellidos, rol FROM usuarios ORDER BY id');
        $stmt->execute();
        $resultados = $stmt->fetchAll();
        return [true, $resultados];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    } finally {
        $con = null;
    }
}

function listaTareasPDO($id_usuario = null, $estado = null)
{
    $con = null;
    try {
        $con = conectaPDO();
        $sql = 'SELECT t.*, u.username FROM tareas t INNER JOIN usuarios u ON u.id = t.id_usuario';
        $params = [];
        $where = [];

        if ($id_usuario !== null) {
            $where[] = 't.id_usuario = :id_usuario';
            $params[':id_usuario'] = (int) $id_usuario;
        }

        if ($estado !== null && $estado !== '') {
            $where[] = 't.estado = :estado';
            $params[':estado'] = $estado;
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY t.id DESC';

        $stmt = $con->prepare($sql);
        $stmt->execute($params);

        $tareas = [];
        while ($row = $stmt->fetch()) {
            $row['id_usuario'] = $row['username'];
            unset($row['username']);
            $tareas[] = $row;
        }

        return [true, $tareas];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    } finally {
        $con = null;
    }
}

function nuevoUsuario($nombre, $apellidos, $username, $contrasena, $rol)
{
    $con = null;
    try {
        $con = conectaPDO();
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt = $con->prepare('INSERT INTO usuarios (nombre, apellidos, username, contrasena, rol) VALUES (:nombre, :apellidos, :username, :contrasena, :rol)');
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':apellidos', $apellidos, PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':contrasena', $hash, PDO::PARAM_STR);
        $stmt->bindValue(':rol', (int) $rol, PDO::PARAM_INT);
        $stmt->execute();

        return [true, null];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    } finally {
        $con = null;
    }
}

function actualizaUsuario($id, $nombre, $apellidos, $username, $contrasena, $rol)
{
    $con = null;
    try {
        $con = conectaPDO();
        $sql = 'UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, username = :username, rol = :rol';
        $params = [
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':username' => $username,
            ':rol' => (int) $rol,
            ':id' => (int) $id,
        ];

        if ($contrasena !== null) {
            $sql .= ', contrasena = :contrasena';
            $params[':contrasena'] = password_hash($contrasena, PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = :id';

        $stmt = $con->prepare($sql);
        $stmt->execute($params);

        return [true, null];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    } finally {
        $con = null;
    }
}

function borraUsuario($id)
{
    $con = null;
    try {
        $con = conectaPDO();
        $con->beginTransaction();

        $stmt = $con->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->execute([':id' => (int) $id]);

        return [$con->commit(), ''];
    } catch (PDOException $e) {
        if ($con !== null && $con->inTransaction()) {
            $con->rollBack();
        }
        return [false, $e->getMessage()];
    } finally {
        $con = null;
    }
}

function buscaUsuario($id)
{
    $con = null;
    try {
        $con = conectaPDO();
        $stmt = $con->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->execute([':id' => (int) $id]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    } catch (PDOException $e) {
        return null;
    } finally {
        $con = null;
    }
}

function buscaUsuarioPorUsername($username)
{
    $con = null;
    try {
        $con = conectaPDO();
        $stmt = $con->prepare('SELECT * FROM usuarios WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    } catch (PDOException $e) {
        return null;
    } finally {
        $con = null;
    }
}

function autenticarUsuario($username, $contrasena)
{
    $usuario = buscaUsuarioPorUsername($username);
    if ($usuario === null) {
        return [false, null];
    }

    $passwordOk = password_verify($contrasena, $usuario['contrasena']);

    // Compatibilidad con datos antiguos en texto plano: migrar a hash al primer login valido.
    if (!$passwordOk && hash_equals((string) $usuario['contrasena'], (string) $contrasena)) {
        $con = null;
        try {
            $con = conectaPDO();
            $nuevoHash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $con->prepare('UPDATE usuarios SET contrasena = :contrasena WHERE id = :id');
            $stmt->execute([
                ':contrasena' => $nuevoHash,
                ':id' => (int) $usuario['id'],
            ]);
            $passwordOk = true;
            $usuario['contrasena'] = $nuevoHash;
        } catch (PDOException $e) {
            return [false, null];
        } finally {
            $con = null;
        }
    }

    if (!$passwordOk) {
        return [false, null];
    }

    unset($usuario['contrasena']);
    return [true, $usuario];
}
