# Procesamiento de Formularios en PHP

## Índice
1. [Conceptos básicos](#conceptos-básicos)
2. [Variables superglobales $_GET y $_POST](#variables-superglobales-get-y-post)
3. [Métodos de comprobación](#métodos-de-comprobación)
4. [Diferenciar múltiples formularios](#diferenciar-múltiples-formularios)
5. [Validación y sanitización](#validación-y-sanitización)
6. [Patrón Post-Redirect-Get](#patrón-post-redirect-get)
7. [Ejemplos completos](#ejemplos-completos)
8. [Mejores prácticas](#mejores-prácticas)

---

## Conceptos básicos

### ¿Qué son $_GET y $_POST?

Son **arrays superglobales** que contienen los datos enviados desde un formulario HTML o URL.

```php
<?php
// $_GET y $_POST siempre existen como arrays (incluso si están vacíos)
var_dump($_GET);   // array(0) {} en primera carga
var_dump($_POST);  // array(0) {} en primera carga
?>
```

**Importante:** Ambos arrays **siempre existen**, pero pueden estar vacíos si no se han enviado datos.

---

### Diferencia entre GET y POST

| Característica | GET | POST |
|----------------|-----|------|
| **Visibilidad** | Datos en URL | Datos ocultos en cuerpo HTTP |
| **Tamaño límite** | ~2000 caracteres | Sin límite práctico |
| **Uso típico** | Búsquedas, filtros, paginación | Formularios, envío de datos |
| **Seguridad** | ❌ Menos seguro (datos visibles) | ✅ Más seguro |
| **Marcadores** | ✅ Se pueden guardar en favoritos | ❌ No se pueden guardar |
| **Caché** | ✅ Se cachea | ❌ No se cachea |
| **Historial** | ✅ Queda en historial | ❌ No queda en historial |

---

### Ejemplo visual

#### Formulario GET:
```html
<form method="GET" action="buscar.php">
    <input name="q" value="php">
    <button type="submit">Buscar</button>
</form>
```

**URL resultante:**
```
buscar.php?q=php
```

**$_GET contiene:**
```php
Array (
    'q' => 'php'
)
```

---

#### Formulario POST:
```html
<form method="POST" action="registro.php">
    <input name="nombre" value="Juan">
    <input name="email" value="juan@example.com">
    <button type="submit">Registrar</button>
</form>
```

**URL resultante:**
```
registro.php  (sin parámetros visibles)
```

**$_POST contiene:**
```php
Array (
    'nombre' => 'Juan',
    'email' => 'juan@example.com'
)
```

---

## Variables superglobales $_GET y $_POST

### ¿Qué contienen?

Todos los datos enviados desde el formulario se convierten en elementos del array, usando el atributo `name` como clave:

```html
<form method="POST">
    <input name="usuario" value="admin">
    <input name="edad" value="25">
    <select name="pais">
        <option value="ES" selected>España</option>
    </select>
    <input type="checkbox" name="acepto" checked>
    <button type="submit" name="submit" value="Enviar">Enviar</button>
</form>
```

**$_POST resultante:**
```php
Array (
    'usuario' => 'admin',
    'edad' => '25',
    'pais' => 'ES',
    'acepto' => 'on',       // Los checkboxes marcados envían 'on'
    'submit' => 'Enviar'    // El botón también se envía si tiene name
)
```

---

### Importante: Tipos de datos

**TODOS los valores en $_GET y $_POST son strings:**

```php
<input name="edad" type="number" value="25">

// $_POST['edad'] es "25" (string), no 25 (int)
var_dump($_POST['edad']);  // string(2) "25"

// Debes convertir si necesitas números
$edad = (int)$_POST['edad'];  // 25 (int)
```

---

### Campos no enviados

**Si un campo no existe en el formulario o no se marca, NO aparece en el array:**

```php
// Checkbox no marcado
<input type="checkbox" name="suscribir">

// Si no se marca, $_POST NO contiene 'suscribir'
if (isset($_POST['suscribir'])) {
    // Solo entra aquí si está marcado
}
```

---

## Métodos de comprobación

### ❌ Método 1: `isset($_POST)` - INCORRECTO

```php
<?php
if (isset($_POST)) {
    // ❌ PROBLEMA: Siempre es true
    // $_POST siempre existe como array, aunque esté vacío
    echo "Esto se ejecuta SIEMPRE";
}
?>
```

**Por qué falla:**
```php
// En la primera carga (sin enviar formulario):
var_dump($_POST);         // array(0) {}
var_dump(isset($_POST));  // bool(true) ← SIEMPRE true
```

---

### ⚠️ Método 2: `!empty($_POST)` - ACEPTABLE

```php
<?php
if (!empty($_POST)) {
    // ✅ Se ejecuta solo si $_POST tiene datos
    echo "Formulario enviado";
}
?>
```

**Ventajas:**
- ✅ Funciona correctamente
- ✅ Simple y directo

**Desventajas:**
- ⚠️ No distingue entre métodos HTTP
- ⚠️ No diferencia múltiples formularios

---

### ✅ Método 3: `$_SERVER["REQUEST_METHOD"]` - RECOMENDADO

```php
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ✅ Se ejecuta solo en peticiones POST
    $nombre = $_POST["nombre"] ?? "";
    echo "Formulario POST procesado";
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["buscar"])) {
    // ✅ Se ejecuta solo en peticiones GET con parámetro
    $termino = $_GET["buscar"];
    echo "Búsqueda: $termino";
}
?>
```

**Ventajas:**
- ✅ Semánticamente correcto
- ✅ Verifica el método HTTP real
- ✅ Más profesional y estándar
- ✅ Diferencia GET de POST

---

### ✅ Método 4: `isset($_POST['campo_especifico'])` - PARA MÚLTIPLES FORMULARIOS

```php
<?php
// Verificar un campo específico del formulario
if (isset($_POST["submit_login"])) {
    // ✅ Procesar formulario de login
}

if (isset($_POST["submit_registro"])) {
    // ✅ Procesar formulario de registro
}
?>
```

**Ventajas:**
- ✅ Diferencia múltiples formularios en la misma página
- ✅ Más específico que verificar el método

---

### Comparación de métodos

```php
<?php
// ❌ INCORRECTO - Siempre true
if (isset($_POST)) { }

// ⚠️ ACEPTABLE - Funciona pero poco específico
if (!empty($_POST)) { }

// ✅ RECOMENDADO - Semántico y claro
if ($_SERVER["REQUEST_METHOD"] == "POST") { }

// ✅ ESPECÍFICO - Para múltiples formularios
if (isset($_POST["submit"])) { }

// ❌ REDUNDANTE - No añade valor
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST)) { }
// El isset($_POST) es innecesario
?>
```

---

## Diferenciar múltiples formularios

Cuando tienes **varios formularios en la misma página**, necesitas identificar cuál se envió.

### Problema: Dos formularios sin diferenciación

```php
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ¿Qué formulario se envió? No lo sabemos
}
?>

<form method="POST">
    <input name="usuario">
    <button type="submit">Login</button>
</form>

<form method="POST">
    <input name="nombre">
    <button type="submit">Registro</button>
</form>
```

---

### Solución 1: Usar `name` en el botón submit

```php
<?php
if (isset($_POST["submit_login"])) {
    // Procesar login
    $usuario = $_POST["usuario"];
    echo "Procesando login de: $usuario";
}

if (isset($_POST["submit_registro"])) {
    // Procesar registro
    $nombre = $_POST["nombre"];
    echo "Procesando registro de: $nombre";
}
?>

<form method="POST">
    <input name="usuario" placeholder="Usuario">
    <button type="submit" name="submit_login">Login</button>
</form>

<form method="POST">
    <input name="nombre" placeholder="Nombre">
    <input name="email" placeholder="Email">
    <button type="submit" name="submit_registro">Registro</button>
</form>
```

**Cómo funciona:**
```php
// Al enviar el primer formulario, $_POST contiene:
Array (
    'usuario' => 'admin',
    'submit_login' => ''  // ← Se añade porque el botón tiene name
)

// Al enviar el segundo formulario, $_POST contiene:
Array (
    'nombre' => 'Juan',
    'email' => 'juan@example.com',
    'submit_registro' => ''  // ← Este botón se envió
)
```

---

### Solución 2: Campo hidden con identificador

```php
<?php
if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case "login":
            $usuario = $_POST["usuario"];
            echo "Procesando login";
            break;
            
        case "registro":
            $nombre = $_POST["nombre"];
            echo "Procesando registro";
            break;
    }
}
?>

<form method="POST">
    <input type="hidden" name="action" value="login">
    <input name="usuario" placeholder="Usuario">
    <button type="submit">Login</button>
</form>

<form method="POST">
    <input type="hidden" name="action" value="registro">
    <input name="nombre" placeholder="Nombre">
    <button type="submit">Registro</button>
</form>
```

---

### Solución 3: Diferentes archivos action

```php
<!-- formulario_login.php -->
<form method="POST" action="procesar_login.php">
    <input name="usuario">
    <button type="submit">Login</button>
</form>

<!-- formulario_registro.php -->
<form method="POST" action="procesar_registro.php">
    <input name="nombre">
    <button type="submit">Registro</button>
</form>
```

**Ventaja:** Cada formulario tiene su propio archivo procesador, sin necesidad de diferenciarlos.

---

### Comparación de soluciones

| Método | Ventajas | Desventajas |
|--------|----------|-------------|
| **name en submit** | Simple, estándar | El valor del botón se envía al servidor |
| **Campo hidden** | Más control, limpio | Requiere campo extra |
| **action diferente** | Separación total | Más archivos que mantener |

---

## Validación y sanitización

### Obtener datos de forma segura

```php
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ❌ Inseguro - Acceso directo
    $nombre = $_POST["nombre"];  // Error si no existe
    
    // ✅ Seguro - Con operador null coalescing
    $nombre = $_POST["nombre"] ?? "";
    
    // ✅ Alternativa - Con isset
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    
    // ✅ Con valor por defecto diferente
    $edad = $_POST["edad"] ?? 18;
}
?>
```

---

### Sanitizar entrada del usuario

```php
<?php
function limpiarInput($dato) {
    $dato = trim($dato);                    // Eliminar espacios
    $dato = stripslashes($dato);            // Eliminar barras invertidas
    $dato = htmlspecialchars($dato);        // Convertir caracteres especiales
    return $dato;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = limpiarInput($_POST["nombre"] ?? "");
    $email = limpiarInput($_POST["email"] ?? "");
    
    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Email inválido";
    }
    
    // Validar longitud
    if (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres";
    }
}
?>
```

---

### Ejemplo completo de validación

```php
<?php
$errores = [];
$nombre = $email = $edad = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos
    $nombre = limpiarInput($_POST["nombre"] ?? "");
    $email = limpiarInput($_POST["email"] ?? "");
    $edad = $_POST["edad"] ?? "";
    
    // Validar nombre
    if (empty($nombre)) {
        $errores[] = "El nombre es requerido";
    } elseif (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres";
    }
    
    // Validar email
    if (empty($email)) {
        $errores[] = "El email es requerido";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido";
    }
    
    // Validar edad
    if (empty($edad)) {
        $errores[] = "La edad es requerida";
    } elseif (!is_numeric($edad) || $edad < 18 || $edad > 100) {
        $errores[] = "La edad debe estar entre 18 y 100";
    }
    
    // Si no hay errores, procesar
    if (empty($errores)) {
        // Guardar en BD, enviar email, etc.
        echo "Registro exitoso";
        // Redirigir para evitar reenvío
        header("Location: exito.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
</head>
<body>
    <?php if (!empty($errores)): ?>
        <div style="color: red;">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <label>Nombre:</label>
        <input name="nombre" value="<?= htmlspecialchars($nombre) ?>"><br>
        
        <label>Email:</label>
        <input name="email" type="email" value="<?= htmlspecialchars($email) ?>"><br>
        
        <label>Edad:</label>
        <input name="edad" type="number" value="<?= htmlspecialchars($edad) ?>"><br>
        
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
```

---

## Patrón Post-Redirect-Get

### Problema: Reenvío de formularios

```php
<?php
// formulario.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar formulario
    guardarEnBD($_POST);
    echo "Datos guardados";
}
?>

<form method="POST">
    <input name="nombre">
    <button type="submit">Enviar</button>
</form>
```

**Problema:** Si el usuario recarga la página (F5), el navegador pregunta si quiere reenviar el formulario, lo que puede causar duplicados.

---

### Solución: Redirigir después de POST

```php
<?php
// formulario.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar formulario
    guardarEnBD($_POST);
    
    // Redirigir a otra página
    header("Location: exito.php");
    exit();  // ← Importante: detener ejecución
}
?>

<form method="POST">
    <input name="nombre">
    <button type="submit">Enviar</button>
</form>
```

**Ventajas:**
- ✅ Recargar la página no reenvía el formulario
- ✅ Mejor experiencia de usuario
- ✅ Evita duplicados en la base de datos

---

### PRG con mensajes de confirmación

```php
<?php
// formulario.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    guardarEnBD($_POST);
    
    // Guardar mensaje en sesión
    $_SESSION["mensaje"] = "Datos guardados correctamente";
    
    // Redirigir
    header("Location: formulario.php");
    exit();
}

// Mostrar mensaje si existe
if (isset($_SESSION["mensaje"])) {
    echo "<p>{$_SESSION["mensaje"]}</p>";
    unset($_SESSION["mensaje"]);  // Eliminar después de mostrar
}
?>

<form method="POST">
    <input name="nombre">
    <button type="submit">Enviar</button>
</form>
```

---

## Ejemplos completos

### Ejemplo 1: Formulario simple con validación

```php
<?php
// procesar_contacto.php
$nombre = $email = $mensaje = "";
$errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mensaje = trim($_POST["mensaje"] ?? "");
    
    // Validar
    if (empty($nombre)) {
        $errores[] = "El nombre es requerido";
    }
    
    if (empty($email)) {
        $errores[] = "El email es requerido";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido";
    }
    
    if (empty($mensaje)) {
        $errores[] = "El mensaje es requerido";
    }
    
    // Procesar si no hay errores
    if (empty($errores)) {
        // Enviar email, guardar en BD, etc.
        mail("admin@example.com", "Contacto", $mensaje, "From: $email");
        
        header("Location: gracias.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contacto</title>
    <style>
        .error { color: red; }
        .campo { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Formulario de Contacto</h1>
    
    <?php if (!empty($errores)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <div class="campo">
            <label>Nombre:</label><br>
            <input name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
        </div>
        
        <div class="campo">
            <label>Email:</label><br>
            <input name="email" type="email" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        
        <div class="campo">
            <label>Mensaje:</label><br>
            <textarea name="mensaje" rows="5" required><?= htmlspecialchars($mensaje) ?></textarea>
        </div>
        
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
```

---

### Ejemplo 2: Múltiples formularios en una página

```php
<?php
// admin.php
session_start();
$mensaje = "";

// Formulario de crear usuario
if (isset($_POST["crear_usuario"])) {
    $usuario = $_POST["usuario"] ?? "";
    $email = $_POST["email"] ?? "";
    
    if (!empty($usuario) && !empty($email)) {
        crearUsuario($usuario, $email);
        $mensaje = "Usuario '$usuario' creado correctamente";
    }
}

// Formulario de eliminar usuario
if (isset($_POST["eliminar_usuario"])) {
    $id = $_POST["id_usuario"] ?? "";
    
    if (!empty($id)) {
        eliminarUsuario($id);
        $mensaje = "Usuario eliminado correctamente";
    }
}

// Formulario de cambiar configuración
if (isset($_POST["guardar_config"])) {
    $sitio = $_POST["nombre_sitio"] ?? "";
    $email_admin = $_POST["email_admin"] ?? "";
    
    if (!empty($sitio) && !empty($email_admin)) {
        actualizarConfiguracion($sitio, $email_admin);
        $mensaje = "Configuración actualizada";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Administración</title>
</head>
<body>
    <h1>Panel de Administración</h1>
    
    <?php if ($mensaje): ?>
        <p style="color: green;"><?= $mensaje ?></p>
    <?php endif; ?>
    
    <!-- Formulario 1: Crear usuario -->
    <section>
        <h2>Crear Usuario</h2>
        <form method="POST">
            <input name="usuario" placeholder="Usuario" required>
            <input name="email" type="email" placeholder="Email" required>
            <button type="submit" name="crear_usuario">Crear</button>
        </form>
    </section>
    
    <!-- Formulario 2: Eliminar usuario -->
    <section>
        <h2>Eliminar Usuario</h2>
        <form method="POST">
            <select name="id_usuario" required>
                <option value="">Seleccionar usuario</option>
                <option value="1">Juan</option>
                <option value="2">María</option>
            </select>
            <button type="submit" name="eliminar_usuario">Eliminar</button>
        </form>
    </section>
    
    <!-- Formulario 3: Configuración -->
    <section>
        <h2>Configuración del Sitio</h2>
        <form method="POST">
            <input name="nombre_sitio" placeholder="Nombre del sitio" required>
            <input name="email_admin" type="email" placeholder="Email admin" required>
            <button type="submit" name="guardar_config">Guardar</button>
        </form>
    </section>
</body>
</html>
```

---

### Ejemplo 3: Búsqueda con GET

```php
<?php
// buscar.php
$resultados = [];
$termino = "";

// Comprobar si hay parámetro de búsqueda
if (isset($_GET["q"])) {
    $termino = trim($_GET["q"]);
    
    if (!empty($termino)) {
        // Buscar en la base de datos
        $resultados = buscarEnBD($termino);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buscador</title>
</head>
<body>
    <h1>Buscador</h1>
    
    <form method="GET" action="buscar.php">
        <input name="q" value="<?= htmlspecialchars($termino) ?>" placeholder="Buscar...">
        <button type="submit">Buscar</button>
    </form>
    
    <?php if ($termino): ?>
        <h2>Resultados para "<?= htmlspecialchars($termino) ?>"</h2>
        
        <?php if (empty($resultados)): ?>
            <p>No se encontraron resultados</p>
        <?php else: ?>
            <ul>
                <?php foreach ($resultados as $resultado): ?>
                    <li><?= htmlspecialchars($resultado["nombre"]) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
```

---

## Mejores prácticas

### ✅ Hacer

#### 1. Usar `$_SERVER["REQUEST_METHOD"]` para verificar el envío

```php
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar formulario
}
?>
```

#### 2. Validar SIEMPRE los datos del lado del servidor

```php
<?php
// Aunque uses required en HTML, valida en PHP
if (empty($_POST["nombre"])) {
    $errores[] = "El nombre es requerido";
}
?>
```

#### 3. Usar operador null coalescing (??)

```php
<?php
$nombre = $_POST["nombre"] ?? "";  // Seguro
?>
```

#### 4. Sanitizar datos antes de mostrarlos

```php
<?php
echo htmlspecialchars($nombre);  // Previene XSS
?>
```

#### 5. Mantener valores en el formulario después de enviar

```php
<input name="nombre" value="<?= htmlspecialchars($nombre) ?>">
```

#### 6. Redirigir después de POST (PRG pattern)

```php
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar
    header("Location: exito.php");
    exit();
}
?>
```

#### 7. Usar name descriptivos en botones submit

```php
<button type="submit" name="guardar_producto">Guardar</button>
<button type="submit" name="eliminar_producto">Eliminar</button>
```

---

### ❌ Evitar

#### 1. Usar isset($_POST) para verificar envío

```php
<?php
// ❌ MAL - Siempre es true
if (isset($_POST)) { }

// ✅ BIEN
if ($_SERVER["REQUEST_METHOD"] == "POST") { }
?>
```

#### 2. Acceder directamente a $_POST sin verificar

```php
<?php
// ❌ MAL - Error si no existe
$nombre = $_POST["nombre"];

// ✅ BIEN
$nombre = $_POST["nombre"] ?? "";
?>
```

#### 3. No validar en el servidor

```php
<?php
// ❌ MAL - Confiar solo en validación HTML
<input required>

// ✅ BIEN - Validar también en PHP
if (empty($_POST["campo"])) {
    $errores[] = "Campo requerido";
}
?>
```

#### 4. Mostrar datos sin sanitizar

```php
<?php
// ❌ PELIGRO - Vulnerable a XSS
echo $_POST["nombre"];

// ✅ BIEN
echo htmlspecialchars($_POST["nombre"]);
?>
```

#### 5. No usar action en formularios procesados en la misma página

```php
<!-- ❌ MAL - Vulnerable si el usuario está en otra página -->
<form method="POST">

<!-- ✅ BIEN - Especificar action -->
<form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
```

#### 6. No redirigir después de procesar POST

```php
<?php
// ❌ MAL - Recargar reenvía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    guardarDatos();
    echo "Guardado";
}

// ✅ BIEN - Redirigir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    guardarDatos();
    header("Location: exito.php");
    exit();
}
?>
```

---

## Resumen

### Comprobación de formularios

| Método | ¿Funciona? | Cuándo usar |
|--------|-----------|-------------|
| `isset($_POST)` | ❌ No | Nunca (siempre true) |
| `!empty($_POST)` | ✅ Sí | Formularios simples |
| `$_SERVER["REQUEST_METHOD"] == "POST"` | ✅ Sí | **Recomendado** |
| `isset($_POST["submit"])` | ✅ Sí | Múltiples formularios |

### Diferenciar formularios

```php
// Método 1: name en submit (recomendado)
<button type="submit" name="submit_login">Login</button>
<button type="submit" name="submit_registro">Registro</button>

if (isset($_POST["submit_login"])) { }
if (isset($_POST["submit_registro"])) { }

// Método 2: campo hidden
<input type="hidden" name="action" value="login">

if ($_POST["action"] == "login") { }
```

### Flujo recomendado

```php
<?php
// 1. Inicializar variables
$errores = [];
$nombre = "";

// 2. Verificar método
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3. Obtener datos
    $nombre = $_POST["nombre"] ?? "";
    
    // 4. Validar
    if (empty($nombre)) {
        $errores[] = "Nombre requerido";
    }
    
    // 5. Procesar si no hay errores
    if (empty($errores)) {
        guardarDatos();
        header("Location: exito.php");
        exit();
    }
}
?>

<!-- 6. Mostrar formulario con errores y valores -->
<form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
    <input name="nombre" value="<?= htmlspecialchars($nombre) ?>">
    <button type="submit">Enviar</button>
</form>
```

---

## Referencias

- [Documentación oficial de $_POST](https://www.php.net/manual/es/reserved.variables.post.php)
- [Documentación oficial de $_GET](https://www.php.net/manual/es/reserved.variables.get.php)
- [Documentación de $_SERVER](https://www.php.net/manual/es/reserved.variables.server.php)
- [Validación de formularios](https://www.php.net/manual/es/filter.examples.validation.php)
