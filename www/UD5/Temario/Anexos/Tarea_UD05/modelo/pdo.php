<?php

require_once(__DIR__ . '/Usuario.php');
require_once(__DIR__ . '/Tarea.php');

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

function usuarioFromRow(array $row): Usuario
{
    return new Usuario(
        (int) ($row['id'] ?? 0),
        (string) ($row['username'] ?? ''),
        (string) ($row['nombre'] ?? ''),
        (string) ($row['apellidos'] ?? ''),
        (string) ($row['contrasena'] ?? ''),
        (int) ($row['rol'] ?? 0)
    );
}

function tareaFromRow(array $row): Tarea
{
    return new Tarea(
        (int) ($row['id'] ?? 0),
        (string) ($row['titulo'] ?? ''),
        (string) ($row['descripcion'] ?? ''),
        (string) ($row['estado'] ?? ''),
        (int) ($row['id_usuario'] ?? 0)
    );
}

function listaUsuarios()
{
    $con = null;
    try {
        $con = conectaPDO();
        $stmt = $con->prepare('SELECT id, username, nombre, apellidos, contrasena, rol FROM usuarios ORDER BY id');
        $stmt->execute();
        $resultados = $stmt->fetchAll();

        $usuarios = [];
        foreach ($resultados as $row) {
            $usuarios[] = usuarioFromRow($row);
        }

        return [true, $usuarios];
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
        $sql = 'SELECT t.id, t.titulo, t.descripcion, t.estado, t.id_usuario FROM tareas t';
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
            $tareas[] = tareaFromRow($row);
        }

        return [true, $tareas];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    } finally {
        $con = null;
    }
}

function nuevoUsuario(Usuario $usuario)
{
    $con = null;
    try {
        $con = conectaPDO();
        $hash = password_hash($usuario->getContrasena(), PASSWORD_DEFAULT);

        $stmt = $con->prepare('INSERT INTO usuarios (nombre, apellidos, username, contrasena, rol) VALUES (:nombre, :apellidos, :username, :contrasena, :rol)');
        $stmt->bindValue(':nombre', $usuario->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(':apellidos', $usuario->getApellidos(), PDO::PARAM_STR);
        $stmt->bindValue(':username', $usuario->getUsername(), PDO::PARAM_STR);
        $stmt->bindValue(':contrasena', $hash, PDO::PARAM_STR);
        $stmt->bindValue(':rol', $usuario->getRol(), PDO::PARAM_INT);
        $stmt->execute();

        return [true, null];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    } finally {
        $con = null;
    }
}

function actualizaUsuario(Usuario $usuario)
{
    $con = null;
    try {
        $con = conectaPDO();
        $sql = 'UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, username = :username, rol = :rol';
        $params = [
            ':nombre' => $usuario->getNombre(),
            ':apellidos' => $usuario->getApellidos(),
            ':username' => $usuario->getUsername(),
            ':rol' => $usuario->getRol(),
            ':id' => $usuario->getId(),
        ];

        if ($usuario->getContrasena() !== '') {
            $sql .= ', contrasena = :contrasena';
            $params[':contrasena'] = password_hash($usuario->getContrasena(), PASSWORD_DEFAULT);
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

function borraUsuario(Usuario $usuario)
{
    $con = null;
    try {
        $con = conectaPDO();
        $con->beginTransaction();

        $stmt = $con->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->execute([':id' => $usuario->getId()]);

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
        return $usuario ? usuarioFromRow($usuario) : null;
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
        return $usuario ? usuarioFromRow($usuario) : null;
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

    $passwordOk = password_verify($contrasena, $usuario->getContrasena());

    // Compatibilidad con datos antiguos en texto plano: migrar a hash al primer login valido.
    if (!$passwordOk && hash_equals($usuario->getContrasena(), (string) $contrasena)) {
        $con = null;
        try {
            $con = conectaPDO();
            $nuevoHash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt = $con->prepare('UPDATE usuarios SET contrasena = :contrasena WHERE id = :id');
            $stmt->execute([
                ':contrasena' => $nuevoHash,
                ':id' => $usuario->getId(),
            ]);
            $passwordOk = true;
            $usuario->setContrasena($nuevoHash);
        } catch (PDOException $e) {
            return [false, null];
        } finally {
            $con = null;
        }
    }

    if (!$passwordOk) {
        return [false, null];
    }

    $usuarioSesion = new Usuario(
        $usuario->getId(),
        $usuario->getUsername(),
        $usuario->getNombre(),
        $usuario->getApellidos(),
        '',
        $usuario->getRol()
    );
    return [true, $usuarioSesion];
}
