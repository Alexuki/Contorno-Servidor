# Conexiones y Consultas MySQL en PHP

## Índice
1. [Conexión a MySQL con MySQLi](#conexión-a-mysql-con-mysqli)
2. [Manejo de errores de conexión](#manejo-de-errores-de-conexión)
3. [Ejecutar consultas SQL](#ejecutar-consultas-sql)
4. [Manejo de errores en consultas](#manejo-de-errores-en-consultas)
5. [Excepciones MySQLi (mysqli_sql_exception)](#excepciones-mysqli-mysqli_sql_exception)
6. [Consultas preparadas](#consultas-preparadas)
7. [Obtener y procesar resultados](#obtener-y-procesar-resultados)
8. [Cerrar conexiones](#cerrar-conexiones)
9. [Ejemplos completos](#ejemplos-completos)

---

## Conexión a MySQL con MySQLi

### Sintaxis básica

```php
<?php
$conexion = new mysqli($host, $usuario, $password, $base_datos);
?>
```

### Ejemplo de nuestro proyecto

```php
<?php
/**
 * Establece conexión con el contenedor de nuestra BBDD.
 */
function get_conexion()
{
    // Parámetros: host, usuario, contraseña
    $conexion = new mysqli('db', 'root', 'test');
  
    // Verificar si hubo error en la conexión
    if ($conexion->connect_errno != null) {
        die("Fallo en la conexión: " . $conexion->connect_error . 
            " (Código: " . $conexion->connect_errno . ")");
    }
    
    return $conexion;
}
?>
```

### Parámetros de conexión

| Parámetro | Descripción | Ejemplo |
|-----------|-------------|---------|
| `$host` | Servidor de base de datos | `'localhost'`, `'db'` (Docker) |
| `$usuario` | Usuario de MySQL | `'root'`, `'mi_usuario'` |
| `$password` | Contraseña del usuario | `'test'`, `'mi_password'` |
| `$base_datos` | (Opcional) Base de datos a usar | `'tienda'` |

---

## Manejo de errores de conexión

### Propiedades de error en MySQLi

Cuando la conexión falla, MySQLi proporciona:

```php
$conexion->connect_errno  // Código numérico del error (0 = sin error)
$conexion->connect_error  // Mensaje descriptivo del error
```

### Ejemplo de verificación

```php
<?php
$conexion = new mysqli('db', 'root', 'test');

// Verificar error
if ($conexion->connect_errno != null) {
    // Opción 1: Mostrar error y terminar
    die("Error de conexión: " . $conexion->connect_error);
    
    // Opción 2: Registrar error y mostrar mensaje genérico
    error_log("Error MySQL: " . $conexion->connect_error);
    echo "No se pudo conectar a la base de datos";
    exit;
}

echo "Conexión exitosa";
?>
```

### Errores comunes de conexión

| Error | Causa | Solución |
|-------|-------|----------|
| `Connection refused` | Servidor no accesible | Verificar que MySQL esté corriendo |
| `Access denied` | Usuario/contraseña incorrectos | Verificar credenciales |
| `Unknown database` | Base de datos no existe | Crear la base de datos primero |
| `Too many connections` | Límite de conexiones alcanzado | Cerrar conexiones no usadas |

---

## Ejecutar consultas SQL

### Método `query()`

```php
$resultado = $conexion->query($sql);
```

**Retorna:**
- `mysqli_result` object (para SELECT, SHOW, DESCRIBE, EXPLAIN)
- `true` (para INSERT, UPDATE, DELETE exitosos)
- `false` (si hay error)

### Ejemplo de función genérica

```php
<?php
/**
 * Ejecuta una consulta SQL en una conexión.
 * Detiene la ejecución si hay error.
 */
function ejecutar_consulta($conexion, $sql)
{
    $resultado = $conexion->query($sql);

    if ($resultado == false) {
        die($conexion->error);
    }

    return $resultado;
}
?>
```

### Tipos de consultas

#### DDL (Data Definition Language)

```php
<?php
// Crear base de datos
function crear_bd($conexion, $nombre_bd) {
    $sql = "CREATE DATABASE IF NOT EXISTS $nombre_bd";
    ejecutar_consulta($conexion, $sql);
}

// Crear tabla
function crear_tabla_usuarios($conexion)
{
    $sql = "CREATE TABLE IF NOT EXISTS usuarios(
        id INT(6) AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL,
        apellidos VARCHAR(100) NOT NULL,
        edad INT(3) NOT NULL,
        provincia VARCHAR(50) NOT NULL
    )";
    
    ejecutar_consulta($conexion, $sql);
}
?>
```

#### DML (Data Manipulation Language)

```php
<?php
// SELECT
function listar_usuarios($conexion)
{
    $sql = "SELECT * FROM usuarios";
    $resultado = ejecutar_consulta($conexion, $sql);
    return $resultado;
}

// INSERT
$sql = "INSERT INTO usuarios (nombre, apellidos, edad, provincia) 
        VALUES ('Juan', 'Pérez', 25, 'Madrid')";
ejecutar_consulta($conexion, $sql);

// UPDATE
$sql = "UPDATE usuarios SET edad=26 WHERE id=1";
ejecutar_consulta($conexion, $sql);

// DELETE
$sql = "DELETE FROM usuarios WHERE id=1";
ejecutar_consulta($conexion, $sql);
?>
```

---

## Manejo de errores en consultas

### Propiedades de error

Cuando una consulta falla:

```php
$conexion->error   // Mensaje del error
$conexion->errno   // Código numérico del error
```

### ¿Cuándo se produce un error?

**`$resultado` será `false` cuando:**
- Sintaxis SQL incorrecta
- Tabla o columna no existe
- Violación de restricción (PRIMARY KEY, FOREIGN KEY, etc.)
- Tipo de dato incorrecto
- Permisos insuficientes

### Ejemplo de manejo de errores

```php
<?php
function ejecutar_consulta($conexion, $sql)
{
    $resultado = $conexion->query($sql);

    if ($resultado === false) {
        // Información detallada del error
        $mensaje = "❌ Error SQL: " . $conexion->error . 
                   " (Código: " . $conexion->errno . ")\n" .
                   "Consulta: " . $sql;
        die($mensaje);
    }

    return $resultado;
}
?>
```

### Errores comunes en consultas

| Error | Descripción | Ejemplo |
|-------|-------------|---------|
| `Table doesn't exist` | Tabla no existe | `SELECT * FROM tabla_inexistente` |
| `Unknown column` | Columna no existe | `SELECT columna_falsa FROM usuarios` |
| `Duplicate entry` | Clave duplicada | Insertar PRIMARY KEY repetida |
| `Syntax error` | Error de sintaxis SQL | `SELCT` en lugar de `SELECT` |
| `Data too long` | Dato excede tamaño | Insertar texto de 100 chars en VARCHAR(50) |

---

## Excepciones MySQLi (mysqli_sql_exception)

### ¿Qué son las excepciones MySQLi?

A partir de **PHP 8.1**, MySQLi puede lanzar **excepciones** (`mysqli_sql_exception`) en lugar de solo retornar `false` cuando hay errores.

### ¿Cuándo se genera mysqli_sql_exception?

La excepción se lanza automáticamente cuando:

1. **PHP 8.1+**: Por defecto, todas las operaciones MySQLi lanzan excepciones
2. **PHP < 8.1**: Solo si se habilita explícitamente con `mysqli_report()`

### Habilitar excepciones (PHP < 8.1)

```php
<?php
// Habilitar modo de excepciones
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Ahora todas las operaciones MySQLi lanzan excepciones
$conexion = new mysqli('db', 'root', 'wrong_password');
// Si falla, lanza mysqli_sql_exception automáticamente
?>
```

### Modos de reporte MySQLi

| Constante | Descripción |
|-----------|-------------|
| `MYSQLI_REPORT_OFF` | No reporta errores (solo retorna false) |
| `MYSQLI_REPORT_ERROR` | Reporta errores pero no lanza excepciones |
| `MYSQLI_REPORT_STRICT` | Lanza excepciones (mysqli_sql_exception) |
| `MYSQLI_REPORT_INDEX` | Reporta errores de índices |
| `MYSQLI_REPORT_ALL` | Reporta todo |

---

### Cuándo usar try-catch

#### Situación 1: PHP 8.1+ (excepciones por defecto)

```php
<?php
// En PHP 8.1+, TODAS las operaciones MySQLi pueden lanzar excepciones

try {
    // Intento de conexión
    $conexion = new mysqli('db', 'root', 'test');
    
    // Crear base de datos
    $sql = "CREATE DATABASE tienda";
    $conexion->query($sql);
    
    echo "Operaciones exitosas";
    
} catch (mysqli_sql_exception $e) {
    // Capturar cualquier error MySQLi
    echo "Error: " . $e->getMessage();
    echo "Código: " . $e->getCode();
}
?>
```

#### Situación 2: PHP < 8.1 con modo STRICT habilitado

```php
<?php
// Habilitar excepciones manualmente
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli('db', 'root', 'test');
    $sql = "CREATE DATABASE tienda";
    $conexion->query($sql);
    
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

#### Situación 3: PHP < 8.1 sin modo STRICT (método tradicional)

```php
<?php
// NO lanza excepciones, debes verificar manualmente

$conexion = new mysqli('db', 'root', 'test');

// Verificar error de conexión
if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Ejecutar consulta
$resultado = $conexion->query("CREATE DATABASE tienda");

// Verificar error de consulta
if ($resultado === false) {
    die("Error en consulta: " . $conexion->error);
}
?>
```

---

### Ejemplo completo con try-catch

```php
<?php
/**
 * Crear base de datos con manejo de excepciones
 */
function crearBD($conexion, $nombre) {
    try {
        // Intentar crear la base de datos
        $sql = "CREATE DATABASE IF NOT EXISTS $nombre";
        $result = $conexion->query($sql);

        if ($result) {
            echo "✅ BBDD $nombre creada correctamente<br>";
        } else {
            echo "❌ ERROR: BBDD $nombre NO creada<br>";
        }

    } catch (mysqli_sql_exception $e) {
        // Capturar excepciones MySQLi
        echo "❌ ERROR: " . $e->getMessage() . "<br>";
        echo "Código de error: " . $e->getCode() . "<br>";
        
    } finally {
        // Se ejecuta siempre (haya o no error)
        if (isset($conexion) && !$conexion->connect_error) {
            $conexion->close();
            echo "Conexión cerrada<br>";
        }
    }
}

// Uso
$conexion = new mysqli('db', 'root', 'test');
crearBD($conexion, 'tienda');
?>
```

---

### Ventajas de usar excepciones (try-catch)

| Ventaja | Descripción |
|---------|-------------|
| ✅ **Código más limpio** | No necesitas verificar `if ($resultado === false)` en cada línea |
| ✅ **Manejo centralizado** | Un solo `catch` maneja múltiples errores |
| ✅ **Información detallada** | Las excepciones tienen stack trace completo |
| ✅ **Bloque finally** | Código que se ejecuta siempre (ej: cerrar conexión) |
| ✅ **Estándar moderno** | Consistente con otras librerías PHP (PDO, etc.) |

---

### Comparación: Tradicional vs Excepciones

#### Método tradicional (sin excepciones)

```php
<?php
function crearUsuario($conexion, $nombre) {
    // Verificación manual en cada paso
    $sql = "INSERT INTO usuarios (nombre) VALUES ('$nombre')";
    $resultado = $conexion->query($sql);
    
    if ($resultado === false) {
        echo "Error: " . $conexion->error;
        return false;
    }
    
    echo "Usuario creado: ID " . $conexion->insert_id;
    return true;
}
?>
```

#### Con excepciones (try-catch)

```php
<?php
function crearUsuario($conexion, $nombre) {
    try {
        // No necesitas verificar cada línea
        $sql = "INSERT INTO usuarios (nombre) VALUES ('$nombre')";
        $conexion->query($sql);
        
        echo "Usuario creado: ID " . $conexion->insert_id;
        return true;
        
    } catch (mysqli_sql_exception $e) {
        // Manejo centralizado de errores
        echo "Error: " . $e->getMessage();
        return false;
    }
}
?>
```

---

### Cuándo NO usar try-catch

**No es necesario usar try-catch cuando:**

1. Usas PHP < 8.1 sin habilitar `MYSQLI_REPORT_STRICT`
2. Prefieres el manejo tradicional con `if ($resultado === false)`
3. Necesitas control más granular sobre cada error específico

```php
<?php
// Método tradicional (válido y común)
function ejecutar_consulta($conexion, $sql)
{
    $resultado = $conexion->query($sql);

    if ($resultado === false) {
        die($conexion->error);
    }

    return $resultado;
}
?>
```

---

### Propiedades y métodos de mysqli_sql_exception

```php
<?php
try {
    $conexion->query("SELECT * FROM tabla_inexistente");
    
} catch (mysqli_sql_exception $e) {
    // Mensaje del error
    echo $e->getMessage();
    // Ejemplo: "Table 'tienda.tabla_inexistente' doesn't exist"
    
    // Código numérico del error
    echo $e->getCode();
    // Ejemplo: 1146
    
    // Archivo donde ocurrió el error
    echo $e->getFile();
    
    // Línea donde ocurrió el error
    echo $e->getLine();
    
    // Stack trace completo
    echo $e->getTraceAsString();
}
?>
```

---

### Ejemplo: Función ejecutarSql con manejo moderno

```php
<?php
/**
 * Ejecuta una consulta SQL con manejo de excepciones.
 * Retorna [éxito, resultado/error]
 */
function ejecutarSql($conexion, $sql) {
    try {
        $result = $conexion->query($sql);
        
        if ($result) {
            // Éxito: retornar true y el resultado
            return [true, $result];
        }
        
        // No debería llegar aquí si las excepciones están habilitadas
        return [false, $conexion->error];
        
    } catch (mysqli_sql_exception $e) {
        // Error: retornar false y el mensaje de error
        return [false, $e->getMessage()];
    }
}

// Uso
$conexion = new mysqli('db', 'root', 'test');
$conexion->select_db('tienda');

list($exito, $resultado) = ejecutarSql($conexion, "SELECT * FROM usuarios");

if ($exito) {
    while ($fila = $resultado->fetch_assoc()) {
        echo $fila['nombre'] . "<br>";
    }
} else {
    echo "Error en consulta: " . $resultado; // $resultado contiene el error
}
?>
```

---

### Ejemplo: Múltiples operaciones con una sola captura

```php
<?php
function inicializarBD($nombre_bd) {
    try {
        // 1. Conectar
        $conexion = new mysqli('db', 'root', 'test');
        echo "Conectado<br>";
        
        // 2. Crear BD
        $conexion->query("CREATE DATABASE IF NOT EXISTS $nombre_bd");
        echo "BD creada<br>";
        
        // 3. Seleccionar BD
        $conexion->select_db($nombre_bd);
        echo "BD seleccionada<br>";
        
        // 4. Crear tabla
        $sql = "CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(50) NOT NULL
        )";
        $conexion->query($sql);
        echo "Tabla creada<br>";
        
        return $conexion;
        
    } catch (mysqli_sql_exception $e) {
        // Un solo catch para TODAS las operaciones
        die("Error en inicialización: " . $e->getMessage());
    }
}

// Uso
$conexion = inicializarBD('tienda');
?>
```

---

### Bloque finally para cerrar conexiones

```php
<?php
function operacionBD() {
    $conexion = null;
    
    try {
        $conexion = new mysqli('db', 'root', 'test');
        $conexion->select_db('tienda');
        
        // Operaciones...
        $conexion->query("INSERT INTO usuarios (nombre) VALUES ('Test')");
        
        echo "Operación exitosa<br>";
        
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        
    } finally {
        // SIEMPRE se ejecuta, haya o no error
        if ($conexion && !$conexion->connect_error) {
            $conexion->close();
            echo "Conexión cerrada<br>";
        }
    }
}
?>
```

---

### Recomendaciones según versión PHP

#### PHP 8.1 o superior ✅ Recomendado

```php
<?php
// Las excepciones están habilitadas por defecto
// Usa try-catch para operaciones que pueden fallar

try {
    $conexion = new mysqli('db', 'root', 'test');
    $conexion->query("CREATE DATABASE tienda");
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

#### PHP 7.x o inferior

**Opción A: Habilitar excepciones (recomendado)**
```php
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli('db', 'root', 'test');
    // ...
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

**Opción B: Método tradicional (más común en código legacy)**
```php
<?php
$conexion = new mysqli('db', 'root', 'test');

if ($conexion->connect_errno) {
    die("Error: " . $conexion->connect_error);
}

$resultado = $conexion->query("...");

if ($resultado === false) {
    die("Error: " . $conexion->error);
}
?>
```

---

### Resumen: ¿Cuándo usar try-catch?

| Situación | ¿Usar try-catch? |
|-----------|------------------|
| PHP 8.1+ | ✅ Sí (excepciones por defecto) |
| PHP < 8.1 con `MYSQLI_REPORT_STRICT` | ✅ Sí (excepciones habilitadas) |
| PHP < 8.1 sin configurar | ❌ No (usa `if ($resultado === false)`) |
| Múltiples operaciones seguidas | ✅ Sí (manejo centralizado) |
| Operación única simple | ⚠️ Opcional (ambos métodos válidos) |
| Necesitas bloque `finally` | ✅ Sí (para cerrar conexiones siempre) |
| Código legacy/antiguo | ❌ No (mantén consistencia) |

---

## Consultas preparadas

### ¿Por qué usar consultas preparadas?

✅ **Seguridad**: Previenen inyección SQL  
✅ **Performance**: Se compilan una vez, se ejecutan múltiples veces  
✅ **Limpieza**: Separan lógica de datos  

---

### ⚠️ VULNERABILIDAD: Concatenación directa de variables

**Ejemplo de código INSEGURO (NO usar en producción):**

```php
<?php
// ❌ PELIGRO: Vulnerable a inyección SQL
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$edad = $_POST['edad'];
$provincia = $_POST['provincia'];

$sql = "INSERT INTO usuarios (nombre, apellidos, edad, provincia) 
        VALUES ('$nombre', '$apellidos', $edad, '$provincia')";

$conexion->query($sql);
?>
```

**¿Por qué es peligroso?**

Si un usuario malicioso ingresa:
```
Nombre: Juan'; DROP TABLE usuarios; --
```

La consulta SQL resultante sería:
```sql
INSERT INTO usuarios (nombre, apellidos, edad, provincia) 
VALUES ('Juan'; DROP TABLE usuarios; --', 'Pérez', 25, 'Madrid')
```

**¡Esto borraría toda la tabla usuarios!**

---

### Documentación del código inseguro

```php
/**
 * Fragmento de consulta SQL INSERT para añadir un nuevo registro a una tabla.
 * 
 * Esta porción de código SQL inserta valores en una tabla de base de datos.
 * Los valores se corresponden con los campos: nombre, apellidos, edad y provincia.
 * 
 * IMPORTANTE - VULNERABILIDAD DE SEGURIDAD:
 * Este código es vulnerable a inyección SQL ya que concatena directamente
 * variables PHP en la consulta sin utilizar prepared statements o escapar los valores.
 * 
 * Ejemplo de uso seguro recomendado:
 * $stmt = $conn->prepare("INSERT INTO tabla (nombre, apellidos, edad, provincia) 
 *                         VALUES (?, ?, ?, ?)");
 * $stmt->bind_param("ssis", $nombre, $apellidos, $edad, $provincia);
 * $stmt->execute();
 * 
 * @see Conexiones y Consultas MySQL - Sección de Seguridad
 * @warning NO utilizar en producción sin sanitizar las variables
 * @var string $nombre Variable que contiene el nombre (sin sanitizar)
 * @var string $apellidos Variable que contiene los apellidos (sin sanitizar)
 * @var int $edad Variable que contiene la edad (sin comillas en la consulta)
 * @var string $provincia Variable que contiene la provincia (sin sanitizar)
 */

// ❌ Código vulnerable
$sql = "INSERT INTO usuarios (nombre, apellidos, edad, provincia) 
        VALUES ('$nombre', '$apellidos', $edad, '$provincia')";
$conexion->query($sql);
```

---

### ✅ Solución: Consultas preparadas (Prepared Statements)

### Sintaxis

```php
<?php
// 1. Preparar la consulta
$stmt = $conexion->prepare("INSERT INTO usuarios (nombre, apellidos, edad, provincia) VALUES (?,?,?,?)");

// 2. Vincular parámetros
$stmt->bind_param("ssss", $nombre, $apellidos, $edad, $provincia);

// 3. Ejecutar
$stmt->execute();
?>
```

### Tipos de parámetros en `bind_param()`

| Tipo | Descripción | Ejemplo |
|------|-------------|---------|
| `s` | String | `"Juan"` |
| `i` | Integer | `25` |
| `d` | Double/Float | `3.14` |
| `b` | Blob (binario) | Imagen, archivo |

### Ejemplo del proyecto

```php
<?php
/**
 * Consulta preparada para crear un usuario.
 */
function dar_alta_usuario($conexion, $nombre, $apellidos, $edad, $provincia)
{
    // Preparar consulta
    $sql = $conexion->prepare("INSERT INTO usuarios (nombre, apellidos, edad, provincia) VALUES (?,?,?,?)");
    
    // Vincular parámetros (4 strings)
    $sql->bind_param("ssss", $nombre, $apellidos, $edad, $provincia);
    
    // Ejecutar y manejar error
    return $sql->execute() or die($conexion->error);
}
?>
```

### Manejo de errores en consultas preparadas

```php
<?php
function dar_alta_usuario($conexion, $nombre, $apellidos, $edad, $provincia)
{
    // 1. Error en preparación
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, apellidos, edad, provincia) VALUES (?,?,?,?)");
    if ($stmt === false) {
        die("Error al preparar: " . $conexion->error);
    }
    
    // 2. Vincular parámetros
    $stmt->bind_param("ssss", $nombre, $apellidos, $edad, $provincia);
    
    // 3. Error en ejecución
    if (!$stmt->execute()) {
        die("Error al ejecutar: " . $stmt->error);
    }
    
    return true;
}
?>
```

### Comparación: Consulta normal vs preparada

#### ❌ Consulta normal (vulnerable a inyección SQL)

```php
<?php
$nombre = $_POST['nombre']; // Si usuario ingresa: ' OR '1'='1
$sql = "SELECT * FROM usuarios WHERE nombre='$nombre'";
$resultado = $conexion->query($sql);
// SQL resultante: SELECT * FROM usuarios WHERE nombre='' OR '1'='1'
// ¡Devuelve TODOS los usuarios!
?>
```

#### ✅ Consulta preparada (segura)

```php
<?php
$nombre = $_POST['nombre'];
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE nombre=?");
$stmt->bind_param("s", $nombre);
$stmt->execute();
// El valor se escapa automáticamente, no hay inyección SQL
?>
```

---

## Obtener y procesar resultados

### Para consultas SELECT

```php
<?php
$sql = "SELECT * FROM usuarios";
$resultado = $conexion->query($sql);

// Verificar si hay resultados
if ($resultado->num_rows > 0) {
    // Procesar cada fila
    while ($fila = $resultado->fetch_assoc()) {
        echo "Nombre: " . $fila['nombre'] . "<br>";
        echo "Edad: " . $fila['edad'] . "<br>";
    }
} else {
    echo "No hay usuarios";
}
?>
```

### Métodos para obtener filas

| Método | Retorna | Ejemplo |
|--------|---------|---------|
| `fetch_assoc()` | Array asociativo | `$fila['nombre']` |
| `fetch_row()` | Array numérico | `$fila[0]` |
| `fetch_array()` | Ambos | `$fila['nombre']` o `$fila[0]` |
| `fetch_object()` | Objeto | `$fila->nombre` |

### Ejemplo de uso

```php
<?php
function listar_usuarios($conexion)
{
    $sql = "SELECT * FROM usuarios";
    $resultado = ejecutar_consulta($conexion, $sql);
    return $resultado;
}

// Uso
$conexion = get_conexion();
seleccionar_bd($conexion, 'tienda');
$usuarios = listar_usuarios($conexion);

if (!is_bool($usuarios) && $usuarios->num_rows > 0) {
    while ($usuario = $usuarios->fetch_assoc()) {
        echo "<p>Nombre: " . $usuario['nombre'] . "</p>";
    }
}
?>
```

### Para INSERT, UPDATE, DELETE

```php
<?php
// Número de filas afectadas
echo $conexion->affected_rows;

// ID del último registro insertado (AUTO_INCREMENT)
echo $conexion->insert_id;
?>
```

---

## Cerrar conexiones

### ¿Por qué cerrar conexiones?

✅ Libera recursos del servidor  
✅ Evita alcanzar el límite de conexiones  
✅ Buena práctica de programación  

### Sintaxis

```php
<?php
function cerrar_conexion($conexion)
{
    $conexion->close();
}

// Uso
$conexion = get_conexion();
// ... hacer operaciones ...
cerrar_conexion($conexion);
?>
```

### Cierre automático

PHP cierra automáticamente las conexiones al finalizar el script, pero es mejor cerrarlas explícitamente.

---

## Ejemplos completos

### Ejemplo 1: Crear base de datos y tabla

```php
<?php
require_once 'lib/base_datos.php';

// 1. Conectar
$conexion = get_conexion();

// 2. Crear base de datos
crear_bd($conexion, 'tienda');

// 3. Seleccionar base de datos
seleccionar_bd($conexion, 'tienda');

// 4. Crear tabla
crear_tabla_usuarios($conexion);

// 5. Cerrar
cerrar_conexion($conexion);

echo "Base de datos y tabla creadas correctamente";
?>
```

---

### Ejemplo 2: Dar de alta un usuario

```php
<?php
require_once 'lib/base_datos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $edad = $_POST['edad'];
    $provincia = $_POST['provincia'];
    
    // 2. Conectar y seleccionar BD
    $conexion = get_conexion();
    seleccionar_bd($conexion, 'tienda');
    
    // 3. Insertar usuario (consulta preparada)
    $resultado = dar_alta_usuario($conexion, $nombre, $apellidos, $edad, $provincia);
    
    // 4. Verificar resultado
    if ($resultado) {
        echo "Usuario creado con ID: " . $conexion->insert_id;
    } else {
        echo "Error al crear usuario: " . $conexion->error;
    }
    
    // 5. Cerrar conexión
    cerrar_conexion($conexion);
}
?>

<form method="POST">
    <input type="text" name="nombre" required>
    <input type="text" name="apellidos" required>
    <input type="number" name="edad" required>
    <input type="text" name="provincia" required>
    <button type="submit">Guardar</button>
</form>
```

---

### Ejemplo 3: Listar usuarios

```php
<?php
require_once 'lib/base_datos.php';

// 1. Conectar
$conexion = get_conexion();
seleccionar_bd($conexion, 'tienda');

// 2. Obtener usuarios
$resultados = listar_usuarios($conexion);

// 3. Mostrar tabla
?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Edad</th>
            <th>Provincia</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!is_bool($resultados) && $resultados->num_rows > 0) {
            while ($row = $resultados->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['apellidos'] . "</td>";
                echo "<td>" . $row['edad'] . "</td>";
                echo "<td>" . $row['provincia'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No hay usuarios</td></tr>";
        }
        
        // 4. Cerrar conexión
        cerrar_conexion($conexion);
        ?>
    </tbody>
</table>
```

---

### Ejemplo 4: Actualizar usuario

```php
<?php
require_once 'lib/base_datos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $edad = $_POST['edad'];
    $provincia = $_POST['provincia'];
    
    $conexion = get_conexion();
    seleccionar_bd($conexion, 'tienda');
    
    // Editar usuario
    $resultado = editar_usuario($conexion, $id, $nombre, $apellidos, $edad, $provincia);
    
    if ($resultado) {
        echo "Usuario actualizado. Filas afectadas: " . $conexion->affected_rows;
    }
    
    cerrar_conexion($conexion);
}
?>
```

---

### Ejemplo 5: Borrar usuario

```php
<?php
require_once 'lib/base_datos.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $conexion = get_conexion();
    seleccionar_bd($conexion, 'tienda');
    
    // Borrar usuario
    $resultado = borrar_usuario($conexion, $id);
    
    if ($resultado) {
        echo "Usuario borrado correctamente";
    }
    
    cerrar_conexion($conexion);
    
    // Redirigir a lista
    header("Location: listar.php");
    exit;
}
?>
```

---

## Resumen de mejores prácticas

### ✅ Hacer:

1. **Verificar errores de conexión** con `connect_errno`
2. **Verificar errores de consulta** con `$resultado === false`
3. **Usar consultas preparadas** para datos del usuario
4. **Cerrar conexiones** cuando termines
5. **Validar y sanitizar** datos antes de insertar
6. **Usar funciones reutilizables** para operaciones comunes
7. **Documentar funciones** con PHPDoc

```php
<?php
// ✅ Buena práctica
function crear_usuario($conexion, $nombre) {
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);
    return $stmt->execute();
}
?>
```

### ❌ Evitar:

1. **Concatenar datos del usuario** directamente en SQL
2. **Ignorar errores** (no verificar `$resultado`)
3. **No cerrar conexiones** (desperdiciar recursos)
4. **Exponer errores SQL** al usuario final (en producción)
5. **No validar datos** antes de insertar

```php
<?php
// ❌ Mala práctica (vulnerable a inyección SQL)
$nombre = $_POST['nombre'];
$sql = "INSERT INTO usuarios (nombre) VALUES ('$nombre')";
$conexion->query($sql);
?>
```

---

## Estructura del archivo base_datos.php

```php
<?php
/*
 * Capa de acceso de datos.
 * Funciones para gestión de la conexión y operaciones en la BBDD.
 */

// CONEXIÓN
function get_conexion() { /* ... */ }
function seleccionar_bd($conexion, $nombre_bd) { /* ... */ }
function cerrar_conexion($conexion) { /* ... */ }

// DDL (Data Definition Language)
function crear_bd($conexion, $nombre_bd) { /* ... */ }
function crear_tabla_usuarios($conexion) { /* ... */ }

// UTILIDADES
function ejecutar_consulta($conexion, $sql) { /* ... */ }

// CRUD (Create, Read, Update, Delete)
function dar_alta_usuario($conexion, $nombre, $apellidos, $edad, $provincia) { /* ... */ }
function listar_usuarios($conexion) { /* ... */ }
function get_usuario($conexion, $id) { /* ... */ }
function editar_usuario($conexion, $id, $nombre, $apellidos, $edad, $provincia) { /* ... */ }
function borrar_usuario($conexion, $id) { /* ... */ }
?>
```

---

## Referencias

- [PHP MySQLi Manual](https://www.php.net/manual/es/book.mysqli.php)
- [MySQLi prepared statements](https://www.php.net/manual/es/mysqli.quickstart.prepared-statements.php)
- [SQL Injection Prevention](https://www.php.net/manual/es/security.database.sql-injection.php)
