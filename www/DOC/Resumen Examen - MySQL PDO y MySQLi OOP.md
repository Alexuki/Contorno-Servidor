# Resumen Examen - MySQL PDO y MySQLi OOP

## Índice
1. [Conexión a la base de datos](#conexión-a-la-base-de-datos)
2. [Manejo de errores](#manejo-de-errores)
3. [Devolución de resultados](#devolución-de-resultados)
4. [Cuándo usar cada método](#cuándo-usar-cada-método)
5. [Consultas preparadas](#consultas-preparadas)
6. [Obtener resultados - fetch methods](#obtener-resultados---fetch-methods)
7. [Patrones consistentes recomendados](#patrones-consistentes-recomendados)
8. [Checklist rápido](#checklist-rápido)

---

## Conexión a la base de datos

### PDO

```php
<?php
try {
    $pdo = new PDO(
        'mysql:host=db;dbname=tienda;charset=utf8mb4',
        'root',
        'test',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
```

**Opciones importantes:**
- `ERRMODE_EXCEPTION`: Lanza excepciones en errores
- `FETCH_ASSOC`: Arrays asociativos por defecto
- `EMULATE_PREPARES => false`: Prepared statements reales

---

### MySQLi OOP

```php
<?php
// Habilitar excepciones (PHP 8.1+ por defecto)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli('db', 'root', 'test', 'tienda');
    $mysqli->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die("Error: " . $e->getMessage());
}
?>
```

---

## Manejo de errores

### PDO - Con excepciones (RECOMENDADO)

```php
<?php
function crearUsuario($pdo, $nombre, $email) {
    try {
        $sql = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $email]);
        
        return [
            'exito' => true,
            'id' => $pdo->lastInsertId()
        ];
        
    } catch (PDOException $e) {
        // Detectar error específico
        if ($e->getCode() == 23000) { // Duplicate entry
            return [
                'exito' => false,
                'error' => 'El email ya existe'
            ];
        }
        
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}
?>
```

**Patrón consistente:**
```php
return [
    'exito' => true/false,
    'datos' => $resultados,      // Opcional
    'error' => 'mensaje',        // Opcional
    'id' => $insertId            // Opcional
];
```

---

### MySQLi OOP - Con excepciones (RECOMENDADO)

```php
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function crearUsuario($mysqli, $nombre, $email) {
    try {
        $sql = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $nombre, $email);
        $stmt->execute();
        
        $id = $mysqli->insert_id;
        $stmt->close();
        
        return [
            'exito' => true,
            'id' => $id
        ];
        
    } catch (mysqli_sql_exception $e) {
        // Detectar error específico
        if ($mysqli->errno == 1062) { // Duplicate entry
            return [
                'exito' => false,
                'error' => 'El email ya existe'
            ];
        }
        
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}
?>
```

---

### Códigos de error comunes

| Código | Significado | Solución |
|--------|-------------|----------|
| 23000 (PDO) / 1062 (MySQLi) | Duplicate entry (clave única) | Email/username duplicado |
| 42S02 (PDO) / 1146 (MySQLi) | Table doesn't exist | Verificar nombre de tabla |
| 42S22 (PDO) / 1054 (MySQLi) | Column doesn't exist | Verificar nombre de columna |
| 1452 | Foreign key constraint fails | Relación no existe |

---

## Devolución de resultados

### Patrón consistente (RECOMENDADO)

```php
<?php
// ✅ Siempre devolver array estructurado
function obtenerUsuario($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            return ['exito' => true, 'datos' => $usuario];
        } else {
            return ['exito' => false, 'error' => 'Usuario no encontrado'];
        }
        
    } catch (PDOException $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}

// Uso consistente
$resultado = obtenerUsuario($pdo, 5);
if ($resultado['exito']) {
    $usuario = $resultado['datos'];
    echo $usuario['nombre'];
} else {
    echo "Error: " . $resultado['error'];
}
?>
```

---

### Alternativas (menos consistentes)

```php
<?php
// ⚠️ Opción 2: Devolver datos directamente o null
function obtenerUsuario($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}

// ⚠️ Opción 3: Lanzar excepción
function obtenerUsuario($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    
    $usuario = $stmt->fetch();
    if (!$usuario) {
        throw new Exception("Usuario no encontrado");
    }
    
    return $usuario;
}
?>
```

---

## Cuándo usar cada método

### PDO: query() vs exec() vs prepare()

| Método | Cuándo usar | Retorna | Ejemplo |
|--------|-------------|---------|---------|
| `query()` | SELECT sin variables | PDOStatement | `$pdo->query("SELECT * FROM usuarios")` |
| `exec()` | INSERT/UPDATE/DELETE sin variables | int (filas afectadas) | `$pdo->exec("DELETE FROM logs WHERE fecha < '2020-01-01'")` |
| `prepare() + execute()` | **Cualquier consulta CON variables** | PDOStatement | `$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?")` |

---

#### query() - Para SELECT sin variables

```php
<?php
// ✅ BIEN - Sin variables
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY nombre");
$usuarios = $stmt->fetchAll();

// ❌ MAL - Con variables (vulnerable a SQL injection)
$id = $_GET['id'];
$stmt = $pdo->query("SELECT * FROM usuarios WHERE id = $id");
?>
```

---

#### exec() - Para INSERT/UPDATE/DELETE sin variables

```php
<?php
// ✅ BIEN - Sin variables
$filas = $pdo->exec("DELETE FROM logs WHERE fecha < '2020-01-01'");
echo "Filas eliminadas: $filas";

// ✅ BIEN - Crear tabla
$pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50)
)");

// ❌ MAL - Con variables
$id = $_POST['id'];
$pdo->exec("DELETE FROM usuarios WHERE id = $id"); // ¡SQL injection!
?>
```

---

#### prepare() + execute() - Con variables (SIEMPRE)

```php
<?php
// ✅ BIEN - Prepared statements
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);

// ✅ BIEN - Named parameters
$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)");
$stmt->execute([':nombre' => $nombre, ':email' => $email]);
?>
```

---

### MySQLi OOP: query() vs prepare()

| Método | Cuándo usar | Retorna | Ejemplo |
|--------|-------------|---------|---------|
| `query()` | Cualquier consulta sin variables | mysqli_result o bool | `$mysqli->query("SELECT * FROM usuarios")` |
| `prepare() + execute()` | **Cualquier consulta CON variables** | mysqli_stmt | `$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE id = ?")` |

**Nota:** MySQLi no tiene `exec()`, usa `query()` para todo.

---

#### query() - Sin variables

```php
<?php
// ✅ SELECT sin variables
$result = $mysqli->query("SELECT * FROM usuarios");
$usuarios = $result->fetch_all(MYSQLI_ASSOC);

// ✅ INSERT sin variables
$mysqli->query("INSERT INTO logs (accion) VALUES ('Sistema iniciado')");

// ✅ Obtener filas afectadas
$mysqli->query("DELETE FROM logs WHERE fecha < '2020-01-01'");
echo "Filas eliminadas: " . $mysqli->affected_rows;
?>
```

---

#### prepare() - Con variables

```php
<?php
// ✅ SELECT con variables
$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// ✅ INSERT con variables
$stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
$stmt->bind_param("ss", $nombre, $email);
$stmt->execute();
$id = $mysqli->insert_id;
$stmt->close();
?>
```

---

## Consultas preparadas

### ¿Cuándo usar prepared statements?

**Regla de oro:** SIEMPRE que uses variables del usuario o datos dinámicos.

```php
<?php
// ✅ Usar prepared statements
$nombre = $_POST['nombre'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = ?");
$stmt->execute([$nombre]);

// ❌ NUNCA concatenar
$nombre = $_POST['nombre'];
$stmt = $pdo->query("SELECT * FROM usuarios WHERE nombre = '$nombre'");
?>
```

---

### PDO - Placeholders

#### Placeholders posicionales (?)

```php
<?php
$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, edad) VALUES (?, ?, ?)");
$stmt->execute([$nombre, $email, $edad]);
?>
```

**Ventajas:**
- ✅ Más corto
- ✅ Menos repetición

**Desventajas:**
- ❌ El orden importa

---

#### Named placeholders (:nombre)

```php
<?php
$stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, edad) VALUES (:nombre, :email, :edad)");
$stmt->execute([
    ':nombre' => $nombre,
    ':email' => $email,
    ':edad' => $edad
]);
?>
```

**Ventajas:**
- ✅ Más claro
- ✅ Orden no importa

**Desventajas:**
- ❌ Más verboso

**Recomendación:** Usa **named placeholders** para mayor claridad.

---

### MySQLi - bind_param()

```php
<?php
$stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, email, edad) VALUES (?, ?, ?)");

// Tipos: s=string, i=integer, d=double, b=blob
$stmt->bind_param("ssi", $nombre, $email, $edad);
$stmt->execute();
$stmt->close();
?>
```

**Tipos de datos:**
- `s` - string
- `i` - integer
- `d` - double/float
- `b` - blob

---

## Obtener resultados - fetch methods

### PDO

#### fetch() - Una fila

```php
<?php
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);

// Array asociativo (por defecto si configuraste FETCH_ASSOC)
$usuario = $stmt->fetch();
// ['id' => 1, 'nombre' => 'Juan', 'email' => 'juan@example.com']

// Objeto
$usuario = $stmt->fetch(PDO::FETCH_OBJ);
// stdClass Object ( [id] => 1, [nombre] => Juan )

// Array numérico
$usuario = $stmt->fetch(PDO::FETCH_NUM);
// [1, 'Juan', 'juan@example.com']
?>
```

---

#### fetchAll() - Todas las filas

```php
<?php
$stmt = $pdo->query("SELECT * FROM usuarios");

// Array de arrays asociativos
$usuarios = $stmt->fetchAll();
// [
//   ['id' => 1, 'nombre' => 'Juan'],
//   ['id' => 2, 'nombre' => 'Ana']
// ]

// Array de objetos
$usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
```

---

#### fetchColumn() - Una columna

```php
<?php
// Primera columna de la primera fila
$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
$total = $stmt->fetchColumn();
echo "Total: $total";

// Obtener array de una columna
$stmt = $pdo->query("SELECT nombre FROM usuarios");
$nombres = $stmt->fetchAll(PDO::FETCH_COLUMN);
// ['Juan', 'Ana', 'Pedro']
?>
```

---

#### Iterar con while

```php
<?php
$stmt = $pdo->query("SELECT * FROM usuarios");

while ($usuario = $stmt->fetch()) {
    echo $usuario['nombre'] . "<br>";
}
?>
```

---

### MySQLi OOP

#### Dos formas de obtener resultados:

##### 1. Con get_result() - RECOMENDADO (más parecido a PDO)

```php
<?php
$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE edad > ?");
$stmt->bind_param("i", $edad);
$stmt->execute();

// ✅ Obtener result object
$result = $stmt->get_result();

// fetch_assoc() - Una fila
$usuario = $result->fetch_assoc();

// fetch_all() - Todas las filas
$usuarios = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
?>
```

---

##### 2. Con bind_result() + fetch() - Antigua (NO RECOMENDADA)

Si no quieres usar `get_result()`, puedes usar `bind_result()` + `fetch()` directamente en el `mysqli_stmt`:

```php
<?php
$stmt = $mysqli->prepare("SELECT id, nombre, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// ⚠️ bind_result() vincula las columnas a VARIABLES
$stmt->bind_result($userId, $userName, $userEmail);

// fetch() guarda los valores en las variables vinculadas
while ($stmt->fetch()) {
    // Ahora las variables tienen los valores
    echo "ID: $userId, Nombre: $userName, Email: $userEmail<br>";
}

$stmt->close();
?>
```

**🔴 Desventajas de bind_result() + fetch():**
- ❌ Debes declarar una variable por cada columna (tedioso y propenso a errores)
- ❌ Debes listar las columnas en el SELECT (no puedes usar `SELECT *`)
- ❌ Si cambias el orden de las columnas, debes cambiar el orden en `bind_result()`
- ❌ NO obtienes un array asociativo, solo variables sueltas
- ❌ Menos legible y más difícil de mantener

**✅ Ventajas de get_result() + fetch_assoc():**
- ✅ Obtienes un array asociativo `['nombre' => 'Juan']`
- ✅ Puedes usar `SELECT *` sin problemas
- ✅ Más flexible y fácil de usar
- ✅ Mismo comportamiento que `query()`

---

#### **Comparación directa: fetch() vs fetch_assoc()**

```php
<?php
// ❌ OPCIÓN A: bind_result() + fetch() (en mysqli_stmt)
$stmt = $mysqli->prepare("SELECT id, nombre, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($userId, $userName, $userEmail); // Vincular variables

while ($stmt->fetch()) {
    // Solo tienes variables sueltas
    echo $userName; // 'Juan'
    // NO tienes array: $row['nombre'] ❌ no existe
}

// ✅ OPCIÓN B: get_result() + fetch_assoc() (en mysqli_result)
$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE id = ?"); // Puedes usar SELECT *
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result(); // Convierte mysqli_stmt → mysqli_result

while ($row = $resultado->fetch_assoc()) {
    // Tienes un array asociativo completo
    echo $row['nombre']; // 'Juan'
    echo $row['email'];  // 'juan@email.com'
    // Puedes acceder a cualquier columna por nombre
}
?>
```

---

#### **Tabla comparativa completa**

| Característica | `bind_result() + fetch()` | `get_result() + fetch_assoc()` |
|----------------|---------------------------|-------------------------------|
| Objeto usado | `mysqli_stmt` | `mysqli_result` |
| Tipo de retorno | Variables sueltas | Array asociativo |
| SELECT * | ❌ NO funciona | ✅ SÍ funciona |
| Declarar variables | ✅ Obligatorio (una por columna) | ❌ No necesario |
| Acceso a datos | `$userName` | `$row['nombre']` |
| Flexibilidad | ❌ Baja | ✅ Alta |
| Legibilidad | ❌ Baja | ✅ Alta |
| Mantenimiento | ❌ Difícil | ✅ Fácil |

**🎯 Recomendación final:** Usa **SIEMPRE get_result() + fetch_assoc()** en prepared statements. Es más flexible, legible y consistente con el comportamiento de `query()`.

---

### ⚠️ ACLARACIÓN IMPORTANTE: query() vs prepare()

Existen **DOS formas diferentes** de obtener resultados en MySQLi, según el método usado:

#### **Opción A: Usando query() - Sin parámetros**

Si usas `query()` para consultas **sin parámetros**, obtienes directamente un objeto `mysqli_result`:

```php
<?php
// query() devuelve mysqli_result directamente
$resultado = $mysqli->query("SELECT * FROM usuarios");

// Puedes usar fetch_assoc() inmediatamente
while ($usuario = $resultado->fetch_assoc()) {
    echo $usuario['nombre'];
}
?>
```

**✅ Cuándo usarlo:**
- Consultas simples sin parámetros
- Cuando NO necesitas protección contra SQL injection
- Ejemplo: `SELECT * FROM usuarios` (obtener todos)

---

#### **Opción B: Usando prepare() - Con parámetros (RECOMENDADO)**

Si usas `prepare()` para consultas **con parámetros**, obtienes un objeto `mysqli_stmt` que **requiere get_result()**:

```php
<?php
// prepare() + execute() devuelve mysqli_stmt
$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// ⚠️ NO puedes usar fetch_assoc() directamente
// DEBES usar get_result() primero
$resultado = $stmt->get_result(); // Ahora sí tienes mysqli_result

while ($usuario = $resultado->fetch_assoc()) {
    echo $usuario['nombre'];
}
?>
```

**✅ Cuándo usarlo (SIEMPRE que sea posible):**
- Consultas con parámetros dinámicos
- Protección contra SQL injection
- Ejemplo: `WHERE id = ?`, `WHERE nombre = ?`

---

#### **Comparación visual**

| Método | Retorna | fetch_assoc() directo | Necesita get_result() |
|--------|---------|----------------------|----------------------|
| `query()` | `mysqli_result` | ✅ SÍ | ❌ NO |
| `prepare() + execute()` | `mysqli_stmt` | ❌ NO | ✅ SÍ |

**🎯 Regla de oro:**
- `query()` → `fetch_assoc()` directo
- `prepare()` → `get_result()` → `fetch_assoc()`

**💡 Para el examen, usa SIEMPRE `prepare()`** por seguridad, excepto que la consulta no tenga parámetros.

---

#### Métodos de fetch en MySQLi

```php
<?php
$result = $mysqli->query("SELECT * FROM usuarios");

// fetch_assoc() - Array asociativo
$usuario = $result->fetch_assoc();
// ['id' => 1, 'nombre' => 'Juan']

// fetch_object() - Objeto
$usuario = $result->fetch_object();
// stdClass Object ( [id] => 1 [nombre] => Juan )

// fetch_array() - Array asociativo y numérico
$usuario = $result->fetch_array(MYSQLI_ASSOC); // Solo asociativo
$usuario = $result->fetch_array(MYSQLI_NUM);   // Solo numérico
$usuario = $result->fetch_array(MYSQLI_BOTH);  // Ambos

// fetch_all() - Todas las filas
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>
```

---

#### Iterar con while

```php
<?php
$result = $mysqli->query("SELECT * FROM usuarios");

while ($usuario = $result->fetch_assoc()) {
    echo $usuario['nombre'] . "<br>";
}
?>
```

---

## Patrones consistentes recomendados

### Patrón 1: Función CRUD con PDO

```php
<?php
/**
 * Crear usuario
 */
function crearUsuario($pdo, $nombre, $email, $edad) {
    try {
        $sql = "INSERT INTO usuarios (nombre, email, edad) VALUES (:nombre, :email, :edad)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':edad' => $edad
        ]);
        
        return [
            'exito' => true,
            'id' => $pdo->lastInsertId(),
            'mensaje' => 'Usuario creado correctamente'
        ];
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage(),
            'codigo' => $e->getCode()
        ];
    }
}

/**
 * Obtener usuario
 */
function obtenerUsuario($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            return ['exito' => true, 'datos' => $usuario];
        } else {
            return ['exito' => false, 'error' => 'Usuario no encontrado'];
        }
        
    } catch (PDOException $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Listar usuarios
 */
function listarUsuarios($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM usuarios ORDER BY nombre");
        $usuarios = $stmt->fetchAll();
        
        return [
            'exito' => true,
            'datos' => $usuarios,
            'total' => count($usuarios)
        ];
        
    } catch (PDOException $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Actualizar usuario
 */
function actualizarUsuario($pdo, $id, $nombre, $email, $edad) {
    try {
        $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, edad = :edad WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':edad' => $edad,
            ':id' => $id
        ]);
        
        if ($stmt->rowCount() > 0) {
            return ['exito' => true, 'mensaje' => 'Usuario actualizado'];
        } else {
            return ['exito' => false, 'error' => 'Usuario no encontrado o sin cambios'];
        }
        
    } catch (PDOException $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Eliminar usuario
 */
function eliminarUsuario($pdo, $id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        if ($stmt->rowCount() > 0) {
            return ['exito' => true, 'mensaje' => 'Usuario eliminado'];
        } else {
            return ['exito' => false, 'error' => 'Usuario no encontrado'];
        }
        
    } catch (PDOException $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}
?>
```

---

### Patrón 2: Función CRUD con MySQLi OOP

```php
<?php
/**
 * Crear usuario
 */
function crearUsuario($mysqli, $nombre, $email, $edad) {
    try {
        $sql = "INSERT INTO usuarios (nombre, email, edad) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $email, $edad);
        $stmt->execute();
        
        $id = $mysqli->insert_id;
        $stmt->close();
        
        return [
            'exito' => true,
            'id' => $id,
            'mensaje' => 'Usuario creado correctamente'
        ];
        
    } catch (mysqli_sql_exception $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage(),
            'codigo' => $mysqli->errno
        ];
    }
}

/**
 * Obtener usuario
 */
function obtenerUsuario($mysqli, $id) {
    try {
        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
        
        if ($usuario) {
            return ['exito' => true, 'datos' => $usuario];
        } else {
            return ['exito' => false, 'error' => 'Usuario no encontrado'];
        }
        
    } catch (mysqli_sql_exception $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Listar usuarios
 */
function listarUsuarios($mysqli) {
    try {
        $result = $mysqli->query("SELECT * FROM usuarios ORDER BY nombre");
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        
        return [
            'exito' => true,
            'datos' => $usuarios,
            'total' => count($usuarios)
        ];
        
    } catch (mysqli_sql_exception $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Actualizar usuario
 */
function actualizarUsuario($mysqli, $id, $nombre, $email, $edad) {
    try {
        $sql = "UPDATE usuarios SET nombre = ?, email = ?, edad = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssii", $nombre, $email, $edad, $id);
        $stmt->execute();
        
        $filasAfectadas = $stmt->affected_rows;
        $stmt->close();
        
        if ($filasAfectadas > 0) {
            return ['exito' => true, 'mensaje' => 'Usuario actualizado'];
        } else {
            return ['exito' => false, 'error' => 'Usuario no encontrado o sin cambios'];
        }
        
    } catch (mysqli_sql_exception $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Eliminar usuario
 */
function eliminarUsuario($mysqli, $id) {
    try {
        $stmt = $mysqli->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $filasAfectadas = $stmt->affected_rows;
        $stmt->close();
        
        if ($filasAfectadas > 0) {
            return ['exito' => true, 'mensaje' => 'Usuario eliminado'];
        } else {
            return ['exito' => false, 'error' => 'Usuario no encontrado'];
        }
        
    } catch (mysqli_sql_exception $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}
?>
```

---

### Uso consistente

```php
<?php
// Crear
$resultado = crearUsuario($pdo, 'Juan', 'juan@example.com', 25);
if ($resultado['exito']) {
    echo "ID: " . $resultado['id'];
} else {
    echo "Error: " . $resultado['error'];
}

// Obtener
$resultado = obtenerUsuario($pdo, 5);
if ($resultado['exito']) {
    $usuario = $resultado['datos'];
    echo $usuario['nombre'];
} else {
    echo "Error: " . $resultado['error'];
}

// Listar
$resultado = listarUsuarios($pdo);
if ($resultado['exito']) {
    foreach ($resultado['datos'] as $usuario) {
        echo $usuario['nombre'] . "<br>";
    }
    echo "Total: " . $resultado['total'];
}
?>
```

---

## Checklist rápido

### ✅ Antes del examen, asegúrate de saber:

#### Conexión
- [ ] Crear conexión PDO con opciones correctas
- [ ] Crear conexión MySQLi con `mysqli_report()`
- [ ] Manejar errores de conexión con try-catch

#### Métodos
- [ ] Cuándo usar `query()` (sin variables)
- [ ] Cuándo usar `exec()` (solo PDO, sin variables)
- [ ] Cuándo usar `prepare() + execute()` (con variables - SIEMPRE)

#### Consultas preparadas
- [ ] PDO: Placeholders `?` y `:nombre`
- [ ] MySQLi: `bind_param()` con tipos (s, i, d, b)

#### Obtener resultados
- [ ] PDO: `fetch()`, `fetchAll()`, `fetchColumn()`
- [ ] MySQLi: `get_result()` + `fetch_assoc()` / `fetch_all()`

#### Información adicional
- [ ] PDO: `lastInsertId()`, `rowCount()`
- [ ] MySQLi: `insert_id`, `affected_rows`

#### Errores
- [ ] Usar try-catch SIEMPRE
- [ ] Devolver array estructurado `['exito' => bool, 'datos' => ...]`
- [ ] Detectar códigos de error específicos (duplicados, etc.)

#### Buenas prácticas
- [ ] NUNCA concatenar variables en SQL
- [ ] SIEMPRE usar prepared statements con datos del usuario
- [ ] Cerrar statements en MySQLi (`$stmt->close()`)
- [ ] Usar `htmlspecialchars()` al mostrar datos

---

## Tabla resumen rápida

### PDO vs MySQLi OOP

| Operación | PDO | MySQLi OOP |
|-----------|-----|------------|
| **Conexión** | `new PDO($dsn, $user, $pass, $options)` | `new mysqli($host, $user, $pass, $db)` |
| **Excepciones** | `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION` | `mysqli_report(MYSQLI_REPORT_ERROR \| MYSQLI_REPORT_STRICT)` |
| **SELECT sin var** | `$pdo->query($sql)` | `$mysqli->query($sql)` |
| **INSERT sin var** | `$pdo->exec($sql)` | `$mysqli->query($sql)` |
| **Prepared** | `$stmt = $pdo->prepare($sql)` | `$stmt = $mysqli->prepare($sql)` |
| **Bind params** | `$stmt->execute([...])` | `$stmt->bind_param("ssi", ...)` |
| **Obtener resultado** | `$stmt->fetch()` | `$stmt->get_result()->fetch_assoc()` |
| **Todas las filas** | `$stmt->fetchAll()` | `$result->fetch_all(MYSQLI_ASSOC)` |
| **Último ID** | `$pdo->lastInsertId()` | `$mysqli->insert_id` |
| **Filas afectadas** | `$stmt->rowCount()` | `$stmt->affected_rows` |
| **Cerrar statement** | Automático | `$stmt->close()` |
| **Cerrar conexión** | `$pdo = null` | `$mysqli->close()` |

---

## Errores comunes a evitar

### ❌ No usar prepared statements

```php
// ❌ PELIGRO
$id = $_GET['id'];
$stmt = $pdo->query("SELECT * FROM usuarios WHERE id = $id");

// ✅ CORRECTO
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
```

---

### ❌ No manejar errores

```php
// ❌ Sin try-catch
$stmt = $pdo->prepare("INSERT INTO usuarios (email) VALUES (?)");
$stmt->execute([$email]);

// ✅ Con try-catch
try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (email) VALUES (?)");
    $stmt->execute([$email]);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
```

---

### ❌ No verificar si hay resultados

```php
// ❌ Error si no existe
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();
echo $usuario['nombre']; // Error si $usuario es false

// ✅ Verificar primero
$usuario = $stmt->fetch();
if ($usuario) {
    echo $usuario['nombre'];
} else {
    echo "Usuario no encontrado";
}
```

---

### ❌ Olvidar cerrar statements en MySQLi

```php
// ❌ Fuga de memoria
$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
// No se cierra

// ✅ Cerrar siempre
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close(); // ← Importante
```

---

### ❌ Usar bind_result() en lugar de get_result()

```php
// ❌ Más complicado
$stmt = $mysqli->prepare("SELECT id, nombre, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($userId, $userName, $userEmail);
$stmt->fetch();

// ✅ Más simple
$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
```

---

## Patrón final recomendado

```php
<?php
// 1. Conexión con excepciones habilitadas
try {
    $pdo = new PDO(
        'mysql:host=db;dbname=tienda;charset=utf8mb4',
        'root',
        'test',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Error conexión: " . $e->getMessage());
}

// 2. Función con try-catch y return estructurado
function operacionBD($pdo, $parametros) {
    try {
        // 3. Usar prepared statements SIEMPRE con variables
        $stmt = $pdo->prepare("SELECT * FROM tabla WHERE columna = :param");
        $stmt->execute([':param' => $parametros]);
        
        // 4. Obtener resultados
        $datos = $stmt->fetchAll();
        
        // 5. Devolver resultado estructurado
        return ['exito' => true, 'datos' => $datos];
        
    } catch (PDOException $e) {
        // 6. Capturar y devolver error
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}

// 7. Uso consistente
$resultado = operacionBD($pdo, $valor);
if ($resultado['exito']) {
    // Procesar datos
    foreach ($resultado['datos'] as $fila) {
        echo htmlspecialchars($fila['columna']);
    }
} else {
    // Mostrar error
    echo "Error: " . htmlspecialchars($resultado['error']);
}
?>
```

---

## Suerte en el examen 🍀

**Recuerda:**
1. ✅ Habilita excepciones SIEMPRE
2. ✅ Usa prepared statements con variables
3. ✅ Try-catch en TODAS las operaciones de BD
4. ✅ Devuelve arrays estructurados
5. ✅ Verifica que hay resultados antes de usar
6. ✅ Cierra statements en MySQLi
7. ✅ Usa `htmlspecialchars()` al mostrar datos
