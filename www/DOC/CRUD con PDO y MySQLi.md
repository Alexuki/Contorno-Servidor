# CRUD con PDO y MySQLi Orientado a Objetos

## Índice
1. [Conexión a la base de datos](#conexión-a-la-base-de-datos)
2. [Diferencia entre query() y exec()](#diferencia-entre-query-y-exec)
3. [Crear base de datos - die() vs devolver error](#crear-base-de-datos---die-vs-devolver-error)
4. [CREATE - Insertar registros](#create---insertar-registros)
5. [READ - Leer registros](#read---leer-registros)
6. [UPDATE - Actualizar registros](#update---actualizar-registros)
7. [DELETE - Eliminar registros](#delete---eliminar-registros)
8. [Manejo de errores](#manejo-de-errores)
9. [Mejores prácticas](#mejores-prácticas)
10. [Comparación PDO vs MySQLi](#comparación-pdo-vs-mysqli)

---

## Conexión a la base de datos

### PDO (PHP Data Objects)

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
    echo "Conexión PDO exitosa";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
```

**Opciones importantes:**
- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`: Lanza excepciones en errores
- `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`: Devuelve arrays asociativos
- `PDO::ATTR_EMULATE_PREPARES => false`: Usa prepared statements reales

---

### MySQLi Orientado a Objetos

```php
<?php
// Habilitar excepciones (PHP 8.1+ por defecto)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli('db', 'root', 'test', 'tienda');
    $mysqli->set_charset('utf8mb4');
    echo "Conexión MySQLi exitosa";
} catch (mysqli_sql_exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
```

---

## Diferencia entre query() y exec()

### PDO

#### `query()` - Para consultas SELECT

**Uso:** Cuando esperas obtener resultados (SELECT, SHOW, DESCRIBE)

```php
<?php
// ✅ query() devuelve un PDOStatement con resultados
$stmt = $pdo->query("SELECT * FROM usuarios");

// Puedes iterar sobre los resultados
foreach ($stmt as $row) {
    echo $row['nombre'];
}

// O usar fetch methods
$usuarios = $stmt->fetchAll();
?>
```

**Retorna:** `PDOStatement` object (o `false` en error si no usas excepciones)

---

#### `exec()` - Para consultas que NO devuelven resultados

**Uso:** INSERT, UPDATE, DELETE, CREATE, DROP, etc.

```php
<?php
// ✅ exec() devuelve el número de filas afectadas
$filasAfectadas = $pdo->exec("DELETE FROM usuarios WHERE edad < 18");
echo "Usuarios eliminados: $filasAfectadas";

// NO puedes obtener resultados con exec()
?>
```

**Retorna:** `int` (número de filas afectadas) o `false` en error

---

### Tabla comparativa: query() vs exec()

| Característica | `query()` | `exec()` |
|----------------|-----------|----------|
| **Tipo de consulta** | SELECT, SHOW, DESCRIBE | INSERT, UPDATE, DELETE, DDL |
| **Retorna** | PDOStatement | int (filas afectadas) |
| **Obtener resultados** | ✅ Sí | ❌ No |
| **Número de filas** | `$stmt->rowCount()` | Directamente el return |
| **Uso con prepared statements** | ❌ No (usa `prepare()`) | ❌ No (usa `prepare()`) |

---

### Cuándo usar cada uno

```php
<?php
// ✅ query() - Necesitas resultados
$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();

// ✅ exec() - No necesitas resultados, solo filas afectadas
$filasEliminadas = $pdo->exec("DELETE FROM usuarios WHERE activo = 0");

// ❌ MAL - exec() con SELECT (pierdes los resultados)
$pdo->exec("SELECT * FROM usuarios"); // No puedes obtener los datos

// ❌ MAL - query() con DELETE (funciona pero menos eficiente)
$stmt = $pdo->query("DELETE FROM usuarios WHERE id = 5");
// Mejor usar exec() o prepared statement
?>
```

---

### ⚠️ IMPORTANTE: NO uses query() ni exec() con datos del usuario

```php
<?php
// ❌ PELIGRO: Vulnerable a inyección SQL
$nombre = $_POST['nombre'];
$pdo->query("SELECT * FROM usuarios WHERE nombre = '$nombre'");

// ✅ CORRECTO: Usa prepared statements
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = ?");
$stmt->execute([$nombre]);
?>
```

**Regla de oro:**
- `query()` y `exec()` solo para consultas **estáticas** (sin variables)
- Para consultas **dinámicas** (con variables), usa **prepared statements** (`prepare()` + `execute()`)

---

### MySQLi

En MySQLi **NO existe `exec()`**, solo `query()` para todo:

```php
<?php
// SELECT
$result = $mysqli->query("SELECT * FROM usuarios");

// INSERT/UPDATE/DELETE
$mysqli->query("DELETE FROM usuarios WHERE id = 5");

// Obtener filas afectadas
echo $mysqli->affected_rows;
?>
```

---

## Crear base de datos - die() vs devolver error

Cuando necesitas crear una base de datos, **la estrategia de manejo de errores depende del contexto** de uso.

---

### Escenario 1: Script de instalación/setup → Usa `die()` o `exit()`

Si es un archivo que **solo se ejecuta una vez** para configurar el sistema (instalación inicial, setup, migración), es apropiado usar `die()`:

#### PDO

```php
<?php
// setup.php - Script de instalación inicial
try {
    $pdo = new PDO('mysql:host=db', 'root', 'test');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS tienda");
    $pdo->exec("USE tienda");
    
    echo "✓ Base de datos 'tienda' creada correctamente\n";
    
    // Continuar con creación de tablas...
    $pdo->exec("CREATE TABLE usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50),
        email VARCHAR(100) UNIQUE
    )");
    
    echo "✓ Tabla 'usuarios' creada correctamente\n";
    
} catch (PDOException $e) {
    die("ERROR CRÍTICO: No se pudo completar la instalación.\n" . 
        "Detalles: " . $e->getMessage());
}

echo "Instalación completada exitosamente.";
?>
```

#### MySQLi

```php
<?php
// setup.php - Script de instalación inicial
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli('db', 'root', 'test');
    
    $mysqli->query("CREATE DATABASE IF NOT EXISTS tienda");
    $mysqli->select_db('tienda');
    
    echo "✓ Base de datos 'tienda' creada correctamente\n";
    
    $mysqli->query("CREATE TABLE usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50),
        email VARCHAR(100) UNIQUE
    )");
    
    echo "✓ Tabla 'usuarios' creada correctamente\n";
    
    $mysqli->close();
    
} catch (mysqli_sql_exception $e) {
    die("ERROR CRÍTICO: No se pudo completar la instalación.\n" . 
        "Detalles: " . $e->getMessage());
}

echo "Instalación completada exitosamente.";
?>
```

**¿Por qué usar `die()`?**
- Si falla la creación de la BD, **no tiene sentido continuar** con el resto del setup
- Es un script que **no forma parte del flujo normal** de la aplicación
- El usuario espera que se **detenga si algo falla**

---

### Escenario 2: Aplicación web/función reutilizable → Devuelve error

Si es parte de una **aplicación web** o una **función en una biblioteca**, **devuelve el error** en lugar de detener toda la aplicación:

#### PDO

```php
<?php
// lib/bbdd.php - Función en biblioteca
/**
 * Crea una base de datos si no existe
 * 
 * @param string $host Servidor de BD
 * @param string $usuario Usuario de BD
 * @param string $password Contraseña
 * @param string $nombreBD Nombre de la base de datos
 * @return array ['exito' => bool, 'mensaje' => string, 'error' => string]
 */
function crearBaseDatos($host, $usuario, $password, $nombreBD) {
    try {
        $pdo = new PDO("mysql:host=$host", $usuario, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Escapar el nombre de la BD (aunque idealmente debería ser validado antes)
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$nombreBD`");
        
        return [
            'exito' => true,
            'mensaje' => "Base de datos '$nombreBD' creada correctamente"
        ];
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage(),
            'codigo' => $e->getCode()
        ];
    }
}

// Uso en la aplicación
$resultado = crearBaseDatos('db', 'root', 'test', 'tienda');

if ($resultado['exito']) {
    echo $resultado['mensaje'];
} else {
    // El código que llama decide qué hacer: mostrar error, log, reintentar, etc.
    error_log("Error creando BD: " . $resultado['error']);
    echo "No se pudo crear la base de datos. Contacte al administrador.";
    
    // Podría mostrar un formulario para reintentar, redirigir, etc.
}
?>
```

#### MySQLi

```php
<?php
// lib/bbdd.php - Función en biblioteca
/**
 * Crea una base de datos si no existe
 */
function crearBaseDatos($host, $usuario, $password, $nombreBD) {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    try {
        $mysqli = new mysqli($host, $usuario, $password);
        
        $mysqli->query("CREATE DATABASE IF NOT EXISTS `$nombreBD`");
        $mysqli->close();
        
        return [
            'exito' => true,
            'mensaje' => "Base de datos '$nombreBD' creada correctamente"
        ];
        
    } catch (mysqli_sql_exception $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage(),
            'codigo' => $e->getCode()
        ];
    }
}

// Uso
$resultado = crearBaseDatos('db', 'root', 'test', 'tienda');

if ($resultado['exito']) {
    echo $resultado['mensaje'];
} else {
    error_log("Error BD: " . $resultado['error']);
    echo "Error al crear la base de datos.";
}
?>
```

**¿Por qué devolver error?**
- Permite al código que llama **decidir qué hacer**: mostrar mensaje, hacer log, reintentar, etc.
- **No mata toda la aplicación** por un error en una operación
- Mejor experiencia de usuario (puede volver, reintentar, contactar soporte)
- Más **flexible y reutilizable**

---

### Tabla comparativa: ¿Cuándo usar cada estrategia?

| Escenario | Usar `die()` / `exit()` | Devolver error |
|-----------|-------------------------|----------------|
| **Script de instalación/setup** | ✅ Recomendado | ❌ |
| **Función en biblioteca** | ❌ | ✅ Recomendado |
| **API endpoint** | ❌ | ✅ Recomendado |
| **Página web con navegación** | ❌ | ✅ Recomendado |
| **Script CLI/cron** | ✅ Aceptable | ✅ También válido |
| **Migración de base de datos** | ✅ Aceptable | ⚠️ Depende del contexto |
| **Operación dentro de transacción** | ❌ | ✅ Recomendado |

---

### Ejemplo híbrido: Lo mejor de ambos mundos

Para un **script de setup robusto** que maneja errores pero termina si fallan operaciones críticas:

```php
<?php
// crear_bd.php - Script de setup con manejo robusto
/**
 * Crea la base de datos y devuelve el resultado
 */
function crearBaseDatos() {
    try {
        $pdo = new PDO('mysql:host=db', 'root', 'test');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $pdo->exec("CREATE DATABASE IF NOT EXISTS tienda");
        
        return ['exito' => true];
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Crea las tablas necesarias
 */
function crearTablas() {
    try {
        $pdo = new PDO('mysql:host=db;dbname=tienda', 'root', 'test');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(50),
            email VARCHAR(100) UNIQUE
        )");
        
        return ['exito' => true];
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}

// ============ EJECUCIÓN DEL SCRIPT ============

echo "=== Iniciando instalación ===\n\n";

// Paso 1: Crear base de datos
echo "1. Creando base de datos... ";
$resultado = crearBaseDatos();

if (!$resultado['exito']) {
    // Log del error técnico
    error_log("Error crítico creando BD: " . $resultado['error']);
    
    // Mensaje al usuario y terminar (aquí sí usamos die)
    die("\n❌ ERROR: No se pudo crear la base de datos.\n" .
        "Verifica la configuración y vuelve a intentar.\n");
}

echo "✓ Completado\n";

// Paso 2: Crear tablas
echo "2. Creando tablas... ";
$resultado = crearTablas();

if (!$resultado['exito']) {
    error_log("Error creando tablas: " . $resultado['error']);
    die("\n❌ ERROR: No se pudieron crear las tablas.\n");
}

echo "✓ Completado\n";

// Si llegamos aquí, todo salió bien
echo "\n=== ✓ Instalación completada exitosamente ===\n";
?>
```

**Ventajas de este enfoque:**
- Las funciones **devuelven errores** (reutilizables y testeables)
- El script principal usa `die()` **solo cuando es crítico** no continuar
- Los errores se **loguean** para depuración
- Mensajes claros para el usuario

---

### ❌ Errores comunes a evitar

#### 1. Usar die() con detalles técnicos en producción

```php
<?php
// ❌ MAL - Expone información sensible al usuario
try {
    $pdo = new PDO('mysql:host=db', 'root', 'test');
    $pdo->exec("CREATE DATABASE tienda");
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
    // Muestra: "SQLSTATE[HY000] [1045] Access denied for user 'root'@'db'"
}

// ✅ BIEN - Log interno, mensaje genérico al usuario
try {
    $pdo = new PDO('mysql:host=db', 'root', 'test');
    $pdo->exec("CREATE DATABASE tienda");
} catch (PDOException $e) {
    error_log("Error BD: " . $e->getMessage()); // Solo para logs
    die("Error al procesar la solicitud. Contacte al administrador.");
}
?>
```

#### 2. No verificar si la BD ya existe antes de continuar

```php
<?php
// ❌ MAL - Intenta usar la BD sin verificar que se creó
$pdo->exec("CREATE DATABASE tienda");
$pdo->exec("USE tienda"); // ¿Y si falló la creación?

// ✅ BIEN - Maneja el resultado
$resultado = crearBaseDatos();
if ($resultado['exito']) {
    // Ahora sí podemos continuar
    $pdo = new PDO('mysql:host=db;dbname=tienda', 'root', 'test');
}
?>
```

#### 3. Usar die() en funciones de biblioteca

```php
<?php
// ❌ MAL - Mata toda la aplicación desde una función
function crearBD($pdo) {
    $pdo->exec("CREATE DATABASE tienda") or die("Error creando BD");
}

// ✅ BIEN - Devuelve el resultado
function crearBD($pdo) {
    try {
        $pdo->exec("CREATE DATABASE tienda");
        return ['exito' => true];
    } catch (PDOException $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}
?>
```

---

### Resumen: Estrategia recomendada

```
┌─────────────────────────────────────────┐
│ ¿Es un script de instalación/setup     │
│ que NO es parte de la aplicación?       │
└────────────┬────────────────────────────┘
             │
      ┌──────┴──────┐
      │             │
     SÍ            NO
      │             │
      │             │
┌─────▼──────┐  ┌───▼──────────────────┐
│ Usa die()  │  │ Devuelve un array    │
│ o exit()   │  │ con el resultado     │
│            │  │ ['exito' => bool]    │
└────────────┘  └──────────────────────┘
```

**Regla general:**
- **Scripts de setup/instalación**: `die()` es aceptable
- **Funciones en aplicaciones**: Devuelve errores estructurados
- **En producción**: NUNCA expongas detalles técnicos con `die()`

---

## CREATE - Insertar registros

### PDO - Con prepared statements (recomendado)

```php
<?php
/**
 * Insertar un usuario usando PDO con prepared statements
 */
function crearUsuario($pdo, $nombre, $apellidos, $edad, $provincia) {
    try {
        // 1. Preparar la consulta
        $sql = "INSERT INTO usuarios (nombre, apellidos, edad, provincia) 
                VALUES (:nombre, :apellidos, :edad, :provincia)";
        
        $stmt = $pdo->prepare($sql);
        
        // 2. Ejecutar con parámetros nombrados
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':edad' => $edad,
            ':provincia' => $provincia
        ]);
        
        // 3. Obtener ID del registro insertado
        $nuevoId = $pdo->lastInsertId();
        
        return [
            'exito' => true,
            'id' => $nuevoId,
            'mensaje' => "Usuario creado con ID: $nuevoId"
        ];
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Uso
$resultado = crearUsuario($pdo, 'Juan', 'Pérez', 25, 'Madrid');

if ($resultado['exito']) {
    echo $resultado['mensaje'];
} else {
    echo "Error: " . $resultado['error'];
}
?>
```

---

### PDO - Con placeholders posicionales

```php
<?php
function crearUsuario($pdo, $nombre, $apellidos, $edad, $provincia) {
    try {
        // Usar ? en lugar de :nombre
        $sql = "INSERT INTO usuarios (nombre, apellidos, edad, provincia) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        // Pasar array en orden
        $stmt->execute([$nombre, $apellidos, $edad, $provincia]);
        
        return [
            'exito' => true,
            'id' => $pdo->lastInsertId()
        ];
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}
?>
```

---

### MySQLi - Con prepared statements

```php
<?php
/**
 * Insertar un usuario usando MySQLi con prepared statements
 */
function crearUsuario($mysqli, $nombre, $apellidos, $edad, $provincia) {
    try {
        // 1. Preparar la consulta
        $sql = "INSERT INTO usuarios (nombre, apellidos, edad, provincia) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($sql);
        
        // 2. Vincular parámetros
        // s = string, i = integer, d = double, b = blob
        $stmt->bind_param("ssis", $nombre, $apellidos, $edad, $provincia);
        
        // 3. Ejecutar
        $stmt->execute();
        
        // 4. Obtener ID insertado
        $nuevoId = $mysqli->insert_id;
        
        // 5. Cerrar statement
        $stmt->close();
        
        return [
            'exito' => true,
            'id' => $nuevoId,
            'mensaje' => "Usuario creado con ID: $nuevoId"
        ];
        
    } catch (mysqli_sql_exception $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Uso
$resultado = crearUsuario($mysqli, 'Ana', 'García', 30, 'Barcelona');

if ($resultado['exito']) {
    echo $resultado['mensaje'];
} else {
    echo "Error: " . $resultado['error'];
}
?>
```

---

## READ - Leer registros

### PDO - Obtener todos los registros

```php
<?php
/**
 * Listar todos los usuarios con PDO
 */
function listarUsuarios($pdo) {
    try {
        $sql = "SELECT id, nombre, apellidos, edad, provincia FROM usuarios";
        $stmt = $pdo->query($sql);
        
        // Obtener todos los resultados como array
        $usuarios = $stmt->fetchAll();
        
        return [
            'exito' => true,
            'datos' => $usuarios,
            'total' => count($usuarios)
        ];
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Uso
$resultado = listarUsuarios($pdo);

if ($resultado['exito']) {
    foreach ($resultado['datos'] as $usuario) {
        echo $usuario['nombre'] . " - " . $usuario['edad'] . " años<br>";
    }
} else {
    echo "Error: " . $resultado['error'];
}
?>
```

---

### PDO - Obtener un registro por ID

```php
<?php
/**
 * Obtener un usuario por ID con PDO
 */
function obtenerUsuario($pdo, $id) {
    try {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        // fetch() devuelve solo una fila (o false si no existe)
        $usuario = $stmt->fetch();
        
        if ($usuario) {
            return [
                'exito' => true,
                'datos' => $usuario
            ];
        } else {
            return [
                'exito' => false,
                'error' => 'Usuario no encontrado'
            ];
        }
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Uso
$resultado = obtenerUsuario($pdo, 5);

if ($resultado['exito']) {
    $usuario = $resultado['datos'];
    echo "Nombre: " . $usuario['nombre'];
} else {
    echo "Error: " . $resultado['error'];
}
?>
```

---

### PDO - Métodos de fetch

```php
<?php
$stmt = $pdo->query("SELECT * FROM usuarios");

// fetch() - Una fila a la vez
$usuario = $stmt->fetch(); // Array asociativo
$usuario = $stmt->fetch(PDO::FETCH_OBJ); // Objeto
$usuario = $stmt->fetch(PDO::FETCH_NUM); // Array numérico

// fetchAll() - Todas las filas
$usuarios = $stmt->fetchAll(); // Array de arrays asociativos
$usuarios = $stmt->fetchAll(PDO::FETCH_OBJ); // Array de objetos

// fetchColumn() - Solo una columna
$stmt = $pdo->query("SELECT nombre FROM usuarios");
$nombres = $stmt->fetchAll(PDO::FETCH_COLUMN);
// Resultado: ['Juan', 'Ana', 'Pedro']

// fetchColumn(0) - Primera columna de la primera fila
$stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
$total = $stmt->fetchColumn();
echo "Total usuarios: $total";
?>
```

---

### MySQLi - Obtener todos los registros

```php
<?php
/**
 * Listar todos los usuarios con MySQLi
 */
function listarUsuarios($mysqli) {
    try {
        $sql = "SELECT id, nombre, apellidos, edad, provincia FROM usuarios";
        $result = $mysqli->query($sql);
        
        // Obtener todos los resultados
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        
        return [
            'exito' => true,
            'datos' => $usuarios,
            'total' => count($usuarios)
        ];
        
    } catch (mysqli_sql_exception $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Uso
$resultado = listarUsuarios($mysqli);

if ($resultado['exito']) {
    foreach ($resultado['datos'] as $usuario) {
        echo $usuario['nombre'] . "<br>";
    }
}
?>
```

---

### MySQLi - Obtener un registro por ID

```php
<?php
/**
 * Obtener un usuario por ID con MySQLi
 */
function obtenerUsuario($mysqli, $id) {
    try {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Obtener resultado
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        
        $stmt->close();
        
        if ($usuario) {
            return [
                'exito' => true,
                'datos' => $usuario
            ];
        } else {
            return [
                'exito' => false,
                'error' => 'Usuario no encontrado'
            ];
        }
        
    } catch (mysqli_sql_exception $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}
?>
```

---

### MySQLi - Métodos de fetch

```php
<?php
$result = $mysqli->query("SELECT * FROM usuarios");

// fetch_assoc() - Array asociativo
$usuario = $result->fetch_assoc();

// fetch_array() - Array asociativo y numérico
$usuario = $result->fetch_array(MYSQLI_ASSOC);
$usuario = $result->fetch_array(MYSQLI_NUM);
$usuario = $result->fetch_array(MYSQLI_BOTH);

// fetch_object() - Objeto
$usuario = $result->fetch_object();
echo $usuario->nombre;

// fetch_all() - Todas las filas
$usuarios = $result->fetch_all(MYSQLI_ASSOC);

// Iterar con while
while ($usuario = $result->fetch_assoc()) {
    echo $usuario['nombre'] . "<br>";
}
?>
```

---

## UPDATE - Actualizar registros

### PDO - Actualizar registro

```php
<?php
/**
 * Actualizar un usuario con PDO
 */
function actualizarUsuario($pdo, $id, $nombre, $apellidos, $edad, $provincia) {
    try {
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, 
                    apellidos = :apellidos, 
                    edad = :edad, 
                    provincia = :provincia 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':edad' => $edad,
            ':provincia' => $provincia,
            ':id' => $id
        ]);
        
        // Obtener número de filas afectadas
        $filasAfectadas = $stmt->rowCount();
        
        if ($filasAfectadas > 0) {
            return [
                'exito' => true,
                'mensaje' => "Usuario actualizado correctamente",
                'filasAfectadas' => $filasAfectadas
            ];
        } else {
            return [
                'exito' => false,
                'error' => 'Usuario no encontrado o sin cambios'
            ];
        }
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Uso
$resultado = actualizarUsuario($pdo, 5, 'Juan Carlos', 'Pérez López', 26, 'Madrid');

if ($resultado['exito']) {
    echo $resultado['mensaje'];
} else {
    echo "Error: " . $resultado['error'];
}
?>
```

---

### MySQLi - Actualizar registro

```php
<?php
/**
 * Actualizar un usuario con MySQLi
 */
function actualizarUsuario($mysqli, $id, $nombre, $apellidos, $edad, $provincia) {
    try {
        $sql = "UPDATE usuarios 
                SET nombre = ?, apellidos = ?, edad = ?, provincia = ? 
                WHERE id = ?";
        
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssisi", $nombre, $apellidos, $edad, $provincia, $id);
        $stmt->execute();
        
        // Obtener filas afectadas
        $filasAfectadas = $stmt->affected_rows;
        
        $stmt->close();
        
        if ($filasAfectadas > 0) {
            return [
                'exito' => true,
                'mensaje' => "Usuario actualizado correctamente",
                'filasAfectadas' => $filasAfectadas
            ];
        } else {
            return [
                'exito' => false,
                'error' => 'Usuario no encontrado o sin cambios'
            ];
        }
        
    } catch (mysqli_sql_exception $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}
?>
```

---

## DELETE - Eliminar registros

### PDO - Eliminar registro

```php
<?php
/**
 * Eliminar un usuario con PDO
 */
function eliminarUsuario($pdo, $id) {
    try {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $filasAfectadas = $stmt->rowCount();
        
        if ($filasAfectadas > 0) {
            return [
                'exito' => true,
                'mensaje' => "Usuario eliminado correctamente"
            ];
        } else {
            return [
                'exito' => false,
                'error' => 'Usuario no encontrado'
            ];
        }
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}

// Uso
$resultado = eliminarUsuario($pdo, 5);

if ($resultado['exito']) {
    echo $resultado['mensaje'];
} else {
    echo "Error: " . $resultado['error'];
}
?>
```

---

### MySQLi - Eliminar registro

```php
<?php
/**
 * Eliminar un usuario con MySQLi
 */
function eliminarUsuario($mysqli, $id) {
    try {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $filasAfectadas = $stmt->affected_rows;
        $stmt->close();
        
        if ($filasAfectadas > 0) {
            return [
                'exito' => true,
                'mensaje' => "Usuario eliminado correctamente"
            ];
        } else {
            return [
                'exito' => false,
                'error' => 'Usuario no encontrado'
            ];
        }
        
    } catch (mysqli_sql_exception $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage()
        ];
    }
}
?>
```

---

## Manejo de errores

### PDO - Tres modos de error

```php
<?php
// Modo 1: Silencioso (no recomendado)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
$stmt = $pdo->query("SELECT * FROM tabla_inexistente");
if ($stmt === false) {
    echo "Error: " . $pdo->errorInfo()[2];
}

// Modo 2: Warning (muestra warnings)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$stmt = $pdo->query("SELECT * FROM tabla_inexistente");
// PHP genera un warning automáticamente

// Modo 3: Excepciones (RECOMENDADO)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
    $stmt = $pdo->query("SELECT * FROM tabla_inexistente");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

---

### Mejores prácticas para devolver resultados

#### Opción 1: Array con estado (Recomendado)

```php
<?php
function operacionBD($pdo, $params) {
    try {
        // Realizar operación
        $stmt = $pdo->prepare("...");
        $stmt->execute($params);
        
        return [
            'exito' => true,
            'datos' => $stmt->fetchAll(),
            'mensaje' => 'Operación exitosa'
        ];
        
    } catch (PDOException $e) {
        return [
            'exito' => false,
            'error' => $e->getMessage(),
            'codigo' => $e->getCode()
        ];
    }
}

// Uso consistente
$resultado = operacionBD($pdo, $params);
if ($resultado['exito']) {
    // Procesar datos
} else {
    // Manejar error
}
?>
```

---

#### Opción 2: Lanzar excepciones (para errores críticos)

```php
<?php
function operacionBD($pdo, $params) {
    try {
        $stmt = $pdo->prepare("...");
        $stmt->execute($params);
        return $stmt->fetchAll();
        
    } catch (PDOException $e) {
        // Re-lanzar con más contexto
        throw new Exception("Error en operación BD: " . $e->getMessage(), 0, $e);
    }
}

// Uso con try-catch externo
try {
    $datos = operacionBD($pdo, $params);
    // Procesar datos
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

---

#### Opción 3: Valor null en error

```php
<?php
function obtenerUsuario($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
        
    } catch (PDOException $e) {
        error_log("Error BD: " . $e->getMessage());
        return null;
    }
}

// Uso
$usuario = obtenerUsuario($pdo, 5);
if ($usuario === null) {
    echo "Usuario no encontrado o error";
} else {
    echo $usuario['nombre'];
}
?>
```

---

### Errores comunes y cómo manejarlos

#### Error 1: Violación de clave única

```php
<?php
function crearUsuario($pdo, $email, $nombre) {
    try {
        $sql = "INSERT INTO usuarios (email, nombre) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $nombre]);
        
        return ['exito' => true, 'id' => $pdo->lastInsertId()];
        
    } catch (PDOException $e) {
        // Código 23000 = Duplicate entry
        if ($e->getCode() == 23000) {
            return [
                'exito' => false,
                'error' => 'El email ya está registrado'
            ];
        }
        
        return [
            'exito' => false,
            'error' => 'Error al crear usuario: ' . $e->getMessage()
        ];
    }
}
?>
```

---

#### Error 2: Tabla o columna no existe

```php
<?php
function consultaSegura($pdo, $sql) {
    try {
        return $pdo->query($sql);
        
    } catch (PDOException $e) {
        $codigo = $e->getCode();
        
        // 42S02 = Table doesn't exist
        if ($codigo == '42S02') {
            return ['error' => 'Tabla no existe'];
        }
        
        // 42S22 = Column doesn't exist
        if ($codigo == '42S22') {
            return ['error' => 'Columna no existe'];
        }
        
        return ['error' => $e->getMessage()];
    }
}
?>
```

---

## Mejores prácticas

### ✅ Hacer

#### 1. Usar siempre prepared statements con datos del usuario

```php
<?php
// ✅ CORRECTO
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$_POST['email']]);
?>
```

#### 2. Habilitar modo de excepciones

```php
<?php
// PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
```

#### 3. Devolver resultados estructurados

```php
<?php
return [
    'exito' => true/false,
    'datos' => [...],
    'error' => 'mensaje',
    'codigo' => 123
];
?>
```

#### 4. Validar datos antes de insertar

```php
<?php
function crearUsuario($pdo, $datos) {
    // Validar primero
    if (empty($datos['email']) || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        return ['exito' => false, 'error' => 'Email inválido'];
    }
    
    // Luego insertar
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (email) VALUES (?)");
        $stmt->execute([$datos['email']]);
        return ['exito' => true];
    } catch (PDOException $e) {
        return ['exito' => false, 'error' => $e->getMessage()];
    }
}
?>
```

#### 5. Cerrar statements explícitamente (MySQLi)

```php
<?php
$stmt = $mysqli->prepare("...");
$stmt->execute();
// ... usar resultados ...
$stmt->close(); // ✅ Libera recursos
?>
```

#### 6. Usar transacciones para operaciones múltiples

```php
<?php
try {
    $pdo->beginTransaction();
    
    $pdo->exec("INSERT INTO pedidos (cliente_id) VALUES (1)");
    $pdo->exec("INSERT INTO detalles_pedido (pedido_id, producto_id) VALUES (1, 5)");
    
    $pdo->commit();
    echo "Pedido creado correctamente";
    
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
```

---

### ❌ Evitar

#### 1. Concatenar variables en SQL

```php
<?php
// ❌ PELIGRO
$nombre = $_POST['nombre'];
$sql = "SELECT * FROM usuarios WHERE nombre = '$nombre'";
?>
```

#### 2. No manejar errores

```php
<?php
// ❌ MAL
$stmt = $pdo->query("SELECT * FROM usuarios");
// ¿Qué pasa si falla?
?>
```

#### 3. Usar query() con datos del usuario

```php
<?php
// ❌ VULNERABLE
$id = $_GET['id'];
$pdo->query("DELETE FROM usuarios WHERE id = $id");
?>
```

#### 4. No verificar si hay resultados

```php
<?php
// ❌ MAL
$usuario = $stmt->fetch();
echo $usuario['nombre']; // Error si no hay usuario
?>
```

#### 5. Exponer errores SQL al usuario final

```php
<?php
// ❌ MAL (en producción)
catch (PDOException $e) {
    echo $e->getMessage(); // Muestra detalles de la BD
}

// ✅ BIEN
catch (PDOException $e) {
    error_log($e->getMessage()); // Log interno
    echo "Error al procesar la solicitud"; // Mensaje genérico al usuario
}
?>
```

---

## Comparación PDO vs MySQLi

| Característica | PDO | MySQLi |
|----------------|-----|--------|
| **Bases de datos soportadas** | MySQL, PostgreSQL, SQLite, Oracle, etc. | Solo MySQL |
| **Prepared statements** | ✅ Sí (`:nombre` o `?`) | ✅ Sí (solo `?`) |
| **Excepciones por defecto** | ✅ Configurables | ⚠️ Solo PHP 8.1+ |
| **Fetch modes** | Múltiples (assoc, obj, class, etc.) | Limitados |
| **Named parameters** | ✅ Sí (`:nombre`) | ❌ No |
| **OOP y Procedural** | Solo OOP | Ambos |
| **Performance** | Similar | Similar |
| **Facilidad de uso** | ✅ Más simple | Más verboso |

---

### Cuándo usar cada uno

**Usa PDO si:**
- Necesitas soportar múltiples bases de datos
- Prefieres named parameters (`:nombre`)
- Quieres código más portable
- Proyecto nuevo

**Usa MySQLi si:**
- Solo usarás MySQL
- Necesitas características específicas de MySQL
- Proyecto legacy que ya usa MySQLi
- Prefieres estilo procedural

---

## Resumen

### Operaciones básicas

| Operación | PDO | MySQLi |
|-----------|-----|--------|
| **Conectar** | `new PDO(...)` | `new mysqli(...)` |
| **Preparar** | `$pdo->prepare($sql)` | `$mysqli->prepare($sql)` |
| **Ejecutar** | `$stmt->execute([...])` | `$stmt->execute()` |
| **Obtener resultados** | `$stmt->fetch()`, `fetchAll()` | `$result->fetch_assoc()` |
| **Filas afectadas** | `$stmt->rowCount()` | `$stmt->affected_rows` |
| **Último ID** | `$pdo->lastInsertId()` | `$mysqli->insert_id` |
| **Cerrar statement** | Automático | `$stmt->close()` |
| **Cerrar conexión** | `$pdo = null` | `$mysqli->close()` |

### Devolver resultados

```php
<?php
// ✅ Formato recomendado
return [
    'exito' => true/false,
    'datos' => $resultados,
    'mensaje' => 'Operación exitosa',
    'error' => null
];
?>
```

### Manejo de errores

```php
<?php
try {
    // Operación de BD
} catch (PDOException | mysqli_sql_exception $e) {
    error_log($e->getMessage()); // Log interno
    return [
        'exito' => false,
        'error' => 'Error al procesar' // Mensaje genérico al usuario
    ];
}
?>
```

---

## Referencias

- [PHP PDO Manual](https://www.php.net/manual/es/book.pdo.php)
- [PHP MySQLi Manual](https://www.php.net/manual/es/book.mysqli.php)
- [Prepared Statements](https://www.php.net/manual/es/pdo.prepared-statements.php)
