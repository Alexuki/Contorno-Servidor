# Navegación y Operaciones CRUD con Enlaces

## Índice
1. [Enlaces `<a>` y métodos HTTP](#enlaces-a-y-métodos-http)
2. [Navegación con formularios `<form>`](#navegación-con-formularios-form)
3. [Navegación a la misma página con GET](#navegación-a-la-misma-página-con-get)
4. [Operaciones destructivas con POST](#operaciones-destructivas-con-post)
5. [Confirmaciones y seguridad](#confirmaciones-y-seguridad)
6. [Ejemplos prácticos completos](#ejemplos-prácticos-completos)
7. [Mejores prácticas](#mejores-prácticas)
8. [Caché del navegador y navegación hacia atrás (BFCache)](#caché-del-navegador-y-navegación-hacia-atrás-bfcache)

---

## Enlaces `<a>` y métodos HTTP

### ¿Qué método HTTP usan los enlaces `<a>`?

Los enlaces `<a>` **SIEMPRE usan el método GET**, sin excepciones. Es el comportamiento por defecto de HTML.

```html
<!-- Esto es GET -->
<a href="pagina.php">Ir a página</a>

<!-- Esto también es GET -->
<a href="eliminar.php?id=5">Eliminar</a>

<!-- Esto también es GET (con parámetros) -->
<a href="buscar.php?q=php&categoria=tutoriales">Buscar</a>
```

---

### Diferencia entre `<a>` y `<form>`

| Elemento | Método por defecto | ¿Puede cambiar método? |
|----------|-------------------|------------------------|
| `<a>` | GET | ❌ No (siempre GET) |
| `<form>` | GET | ✅ Sí (`method="POST"`) |

```html
<!-- Enlaces: siempre GET -->
<a href="pagina.php">Enlace</a>

<!-- Formularios: pueden ser GET o POST -->
<form method="GET" action="pagina.php">
    <button>GET</button>
</form>

<form method="POST" action="pagina.php">
    <button>POST</button>
</form>
```

---

### ¿Cómo pasar datos con enlaces?

Los enlaces pasan datos mediante **parámetros en la URL** (query string):

```html
<a href="ver.php?id=5">Ver usuario 5</a>
<a href="buscar.php?q=php&orden=recientes">Buscar</a>
<a href="filtrar.php?categoria=libros&precio_min=10&precio_max=50">Filtrar</a>
```

**URL resultante:**
```
ver.php?id=5
buscar.php?q=php&orden=recientes
filtrar.php?categoria=libros&precio_min=10&precio_max=50
```

**En PHP:**
```php
<?php
$id = $_GET['id'];           // 5
$q = $_GET['q'];             // 'php'
$categoria = $_GET['categoria']; // 'libros'
?>
```

---

### Parámetros de tipo bandera (flag): `?clave=1`

A veces no interesa pasar un **valor**, sino simplemente **señalar que debe ocurrir una acción**. En ese caso se usa un parámetro cuyo valor es irrelevante (normalmente `1`); lo único que importa es si está presente en la URL o no. PHP lo comprueba con `isset()`.

```html
<!-- El valor "1" es arbitrario; solo importa que el parámetro exista -->
<a href="?destruir=1">Destruir sesión completa</a>
```

Cuando el usuario hace clic, la URL queda:
```
pagina.php?destruir=1
```

PHP detecta la presencia del parámetro con `isset()`:

```php
<?php
session_start();

if (isset($_GET['destruir'])) {
    // El valor de $_GET['destruir'] no importa (podría ser '1', 'yes', 'true'…)
    session_unset();   // Elimina todas las variables de sesión
    session_destroy(); // Destruye el fichero de sesión en el servidor
    header('Location: ' . $_SERVER['PHP_SELF']); // Redirige para limpiar la URL
    exit();
}
?>
```

**Puntos clave:**
- `?destruir=1` → parámetro `destruir` con valor `1` (el valor no se usa).
- `isset($_GET['destruir'])` → devuelve `true` si el parámetro existe en la URL, independientemente de su valor.
- Tras ejecutar la acción se hace un `header('Location: …')` + `exit()` para limpiar la URL (patrón PRG — Post/Redirect/Get adaptado a GET).
- Si el usuario accede a la página sin el parámetro, el `if` simplemente no se ejecuta.

**Comparativa con parámetro de valor:**

| Tipo | Ejemplo | Qué se lee en PHP |
|------|---------|-------------------|
| Valor concreto | `?id=5` | `$_GET['id']` → `"5"` |
| Bandera (flag) | `?destruir=1` | Solo `isset($_GET['destruir'])` |

---

## Navegación con formularios `<form>`

### Método por defecto

Si no se especifica el atributo `method`, el formulario usa **GET**:

```html
<!-- Equivalentes: los dos envían con GET -->
<form action="buscar.php">
    <input name="q"><button>Buscar</button>
</form>

<form method="GET" action="buscar.php">
    <input name="q"><button>Buscar</button>
</form>
```

---

### ¿A dónde se envía si no hay `action`?

Si se omite el atributo `action` (o se deja vacío `action=""`), el formulario se envía **a la misma página** que lo contiene, exactamente igual que si se hubiera escrito `action="<?= $_SERVER['PHP_SELF'] ?>"`. La URL actual (incluyendo cualquier query string existente) se usa como destino.

```html
<!-- Los tres son equivalentes: envían al archivo actual -->
<form method="POST">
    ...
</form>

<form method="POST" action="">
    ...
</form>

<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
    ...
</form>
```

**En PHP** se procesa igual que cualquier otro envío:

```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // El formulario fue enviado
    $nombre = $_POST['nombre'];
}
?>
<!doctype html>
<html>
<body>
    <form method="POST">  <!-- sin action: vuelve a esta misma página -->
        <input type="text" name="nombre">
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
```

---

### Resumen de combinaciones

| `method` | `action` | Resultado |
|----------|----------|-----------|
| Omitido (GET) | `buscar.php` | GET a `buscar.php` |
| `GET` | Omitido / `""` | GET a la página actual |
| `POST` | `guardar.php` | POST a `guardar.php` |
| `POST` | Omitido / `""` | POST a la página actual |

---

### Diferencia práctica entre `<a>` y `<form>` para acciones

```html
<!-- Enlace: siempre GET, parámetros visibles en URL -->
<a href="?destruir=1">Destruir sesión</a>

<!-- Formulario GET: parámetros también en URL, pero se pueden pre-rellenar con inputs ocultos -->
<form method="GET">
    <input type="hidden" name="destruir" value="1">
    <button>Destruir sesión</button>
</form>

<!-- Formulario POST: parámetros en el cuerpo, NO visibles en URL (recomendado para acciones destructivas) -->
<form method="POST">
    <input type="hidden" name="destruir" value="1">
    <button>Destruir sesión</button>
</form>
```

---

## Navegación a la misma página con GET

### Caso típico: Eliminar registro desde tabla

Tienes una tabla con varios registros y quieres que al hacer clic en "Eliminar", se procese en **la misma página**.

---

### Estructura básica

```php
<?php
// 1. Procesar eliminación si viene el parámetro GET
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    // Eliminar de la base de datos
    eliminarRegistro($id);
    
    // Redireccionar para limpiar la URL
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// 2. Obtener datos para mostrar
$registros = obtenerTodosLosRegistros();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de registros</title>
</head>
<body>
    <h1>Lista de registros</h1>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        
        <?php foreach ($registros as $registro): ?>
        <tr>
            <td><?= $registro['id'] ?></td>
            <td><?= $registro['nombre'] ?></td>
            <td>
                <!-- Enlace que navega a la misma página con parámetro -->
                <a href="<?= $_SERVER['PHP_SELF'] ?>?eliminar=<?= $registro['id'] ?>">
                    Eliminar
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
```

---

### Desglose del enlace

```php
<a href="<?= $_SERVER['PHP_SELF'] ?>?eliminar=<?= $registro['id'] ?>">
    Eliminar
</a>
```

**Partes:**
1. `$_SERVER['PHP_SELF']` → Nombre del archivo actual (ej: `lista.php`)
2. `?eliminar=` → Parámetro GET llamado "eliminar"
3. `<?= $registro['id'] ?>` → Valor del ID (ej: `5`)

**Resultado:**
```html
<a href="lista.php?eliminar=5">Eliminar</a>
```

**URL al hacer clic:**
```
lista.php?eliminar=5
```

---

### ¿Es necesario `$_SERVER['PHP_SELF']`? ¿No basta con `?clave=valor`?

No, no es necesario. Una URL que empieza por `?` es una **URL relativa sin ruta**: el navegador mantiene el archivo actual y solo sustituye la query string. Estas tres formas son equivalentes:

```html
<!-- Las tres navegan a la misma página -->
<a href="?eliminar=5">Eliminar</a>
<a href="<?= $_SERVER['PHP_SELF'] ?>?eliminar=5">Eliminar</a>
<a href="lista.php?eliminar=5">Eliminar</a>
```

| Forma | Cuándo usarla |
|-------|--------------|
| `?eliminar=5` | ✅ Recomendado para enlaces a la misma página: corto y suficiente |
| `$_SERVER['PHP_SELF']?eliminar=5` | Cuando se quiere ser explícito; habitual en `action` de formularios |
| `lista.php?eliminar=5` | Solo si el destino es otro archivo o el nombre es fijo |

> **Nota:** `$_SERVER['PHP_SELF']` se usa más en el atributo `action` de `<form>` porque ahí omitirlo también funciona, pero dejarlo explícito deja más clara la intención.

---

### ¿Por qué usar `$_SERVER['PHP_SELF']`?

```php
// ✅ BIEN - Dinámico (funciona si renombras el archivo)
<a href="<?= $_SERVER['PHP_SELF'] ?>?eliminar=5">Eliminar</a>

// ⚠️ También funciona - Pero hardcodeado
<a href="lista.php?eliminar=5">Eliminar</a>
```

**Ventaja de `$_SERVER['PHP_SELF']`:**
- Si renombras el archivo, no necesitas cambiar los enlaces
- Más flexible y reutilizable

---

### Importante: Escapar `$_SERVER['PHP_SELF']`

```php
// ❌ PELIGRO - Vulnerable a XSS
<a href="<?= $_SERVER['PHP_SELF'] ?>?id=5">

// ✅ SEGURO - Escapado
<a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?id=5">
```

---

### Flujo completo

```
1. Usuario ve la tabla con botón "Eliminar" (ID=5)
2. Usuario hace clic en "Eliminar"
3. Navegador hace GET a: lista.php?eliminar=5
4. PHP detecta $_GET['eliminar'] = 5
5. PHP ejecuta eliminarRegistro(5)
6. PHP redirige a lista.php (sin parámetros)
7. Se muestra la tabla actualizada
```

---

### Redirección después de procesar

**¿Por qué redirigir?**

```php
if (isset($_GET['eliminar'])) {
    eliminarRegistro($_GET['eliminar']);
    
    // ✅ Redirigir para limpiar URL
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
```

**Sin redirección:**
- URL queda como `lista.php?eliminar=5`
- Si el usuario recarga (F5), intenta eliminar de nuevo
- Puede causar errores si el registro ya no existe

**Con redirección:**
- URL limpia: `lista.php`
- Recargar no causa problemas
- Mejor experiencia de usuario

---

## Operaciones destructivas con POST

### ¿Por qué usar POST para eliminar?

**Problemas con GET:**
```html
<a href="eliminar.php?id=5">Eliminar</a>
```

❌ **Problemas:**
1. La URL es visible y se puede guardar/compartir
2. Los motores de búsqueda pueden seguir el enlace
3. El navegador puede precargar el enlace
4. El historial guarda la acción de eliminar
5. Semánticamente incorrecto (GET es para lectura, no modificación)

---

### Solución: Usar POST con formulario

```php
<?php
// Procesar eliminación con POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = $_POST['eliminar'];
    eliminarRegistro($id);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$registros = obtenerTodosLosRegistros();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de registros</title>
    <style>
        /* Hacer que el formulario parezca un enlace */
        .form-inline {
            display: inline;
        }
        .btn-link {
            background: none;
            border: none;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        
        <?php foreach ($registros as $registro): ?>
        <tr>
            <td><?= $registro['id'] ?></td>
            <td><?= $registro['nombre'] ?></td>
            <td>
                <!-- Formulario que parece enlace -->
                <form method="POST" class="form-inline">
                    <input type="hidden" name="eliminar" value="<?= $registro['id'] ?>">
                    <button type="submit" class="btn-link">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
```

---

### Con Bootstrap (más profesional)

```php
<td>
    <form method="POST" style="display: inline;">
        <input type="hidden" name="eliminar" value="<?= $registro['id'] ?>">
        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
    </form>
</td>
```

---

### Ventajas de POST vs GET

| Característica | GET | POST |
|----------------|-----|------|
| **URL visible** | ✅ Sí | ❌ No |
| **Se puede compartir** | ✅ Sí | ❌ No |
| **Historial** | ✅ Queda | ❌ No queda |
| **Bots/Crawlers** | ⚠️ Pueden seguir | ✅ No siguen |
| **Semántica HTTP** | ❌ Incorrecto para DELETE | ✅ Correcto |
| **Recarga segura** | ❌ Repite acción | ✅ Navegador pregunta |

---

## Confirmaciones y seguridad

### Confirmación con JavaScript (GET o POST)

```html
<!-- Con enlace GET -->
<a href="<?= $_SERVER['PHP_SELF'] ?>?eliminar=<?= $id ?>"
   onclick="return confirm('¿Estás seguro de eliminar este registro?')">
    Eliminar
</a>

<!-- Con formulario POST -->
<form method="POST" style="display: inline;"
      onsubmit="return confirm('¿Estás seguro?')">
    <input type="hidden" name="eliminar" value="<?= $id ?>">
    <button type="submit">Eliminar</button>
</form>
```

**`return confirm()`:**
- Si el usuario hace clic en "Aceptar" → Devuelve `true` → Continúa
- Si hace clic en "Cancelar" → Devuelve `false` → Cancela la acción

---

### Validación en el servidor (SIEMPRE)

```php
<?php
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    // ✅ Validar que el ID es válido
    if (!is_numeric($id) || $id <= 0) {
        die("ID inválido");
    }
    
    // ✅ Verificar que el registro existe
    if (!existeRegistro($id)) {
        die("El registro no existe");
    }
    
    // ✅ Verificar permisos (si aplica)
    if (!tienePermiso($usuarioActual, $id)) {
        die("No tienes permiso para eliminar este registro");
    }
    
    // Ahora sí, eliminar
    eliminarRegistro($id);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
```

**Nunca confíes en los datos del cliente**, aunque uses JavaScript.

---

### Protección CSRF (para POST)

```php
<?php
session_start();

// Generar token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF inválido");
    }
    
    $id = $_POST['eliminar'];
    eliminarRegistro($id);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!-- Incluir token en el formulario -->
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="hidden" name="eliminar" value="<?= $id ?>">
    <button type="submit">Eliminar</button>
</form>
```

---

## Ejemplos prácticos completos

### Ejemplo 1: Lista de usuarios con eliminación (GET simple)

```php
<?php
// usuarios.php
require_once "db.php";

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    
    if ($id > 0) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
    }
    
    header("Location: usuarios.php");
    exit();
}

// Obtener usuarios
$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Lista de Usuarios</h1>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $usuario['id'] ?>" 
                           class="btn btn-sm btn-primary">Editar</a>
                        
                        <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?eliminar=<?= $usuario['id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('¿Eliminar a <?= htmlspecialchars($usuario['nombre']) ?>?')">
                            Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
```

---

### Ejemplo 2: Lista de productos con eliminación (POST seguro)

```php
<?php
// productos.php
session_start();
require_once "db.php";

// Generar token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Procesar eliminación con POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    // Verificar CSRF
    if ($_POST['csrf_token'] === $_SESSION['csrf_token']) {
        $id = (int)$_POST['eliminar'];
        
        if ($id > 0) {
            $sql = "DELETE FROM productos WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            
            $_SESSION['mensaje'] = "Producto eliminado correctamente";
        }
    }
    
    header("Location: productos.php");
    exit();
}

// Obtener productos
$stmt = $pdo->query("SELECT * FROM productos ORDER BY nombre");
$productos = $stmt->fetchAll();

// Mostrar mensaje si existe
$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Lista de Productos</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= $mensaje ?></div>
        <?php endif; ?>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= $producto['id'] ?></td>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                    <td><?= $producto['stock'] ?></td>
                    <td>
                        <a href="editar_producto.php?id=<?= $producto['id'] ?>" 
                           class="btn btn-sm btn-primary">Editar</a>
                        
                        <form method="POST" style="display: inline;"
                              onsubmit="return confirm('¿Eliminar <?= htmlspecialchars($producto['nombre']) ?>?')">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <input type="hidden" name="eliminar" value="<?= $producto['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
```

---

### Ejemplo 3: Múltiples acciones en la misma página

```php
<?php
// tareas.php
require_once "db.php";

// Procesar diferentes acciones
if (isset($_GET['completar'])) {
    $id = (int)$_GET['completar'];
    $pdo->prepare("UPDATE tareas SET completada = 1 WHERE id = ?")->execute([$id]);
    header("Location: tareas.php");
    exit();
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $pdo->prepare("DELETE FROM tareas WHERE id = ?")->execute([$id]);
    header("Location: tareas.php");
    exit();
}

if (isset($_GET['restaurar'])) {
    $id = (int)$_GET['restaurar'];
    $pdo->prepare("UPDATE tareas SET completada = 0 WHERE id = ?")->execute([$id]);
    header("Location: tareas.php");
    exit();
}

// Obtener tareas
$stmt = $pdo->query("SELECT * FROM tareas ORDER BY fecha_creacion DESC");
$tareas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mis Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Mis Tareas</h1>
        
        <ul class="list-group">
            <?php foreach ($tareas as $tarea): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="<?= $tarea['completada'] ? 'text-decoration-line-through' : '' ?>">
                    <?= htmlspecialchars($tarea['titulo']) ?>
                </span>
                
                <div>
                    <?php if ($tarea['completada']): ?>
                        <a href="?restaurar=<?= $tarea['id'] ?>" 
                           class="btn btn-sm btn-warning">Restaurar</a>
                    <?php else: ?>
                        <a href="?completar=<?= $tarea['id'] ?>" 
                           class="btn btn-sm btn-success">Completar</a>
                    <?php endif; ?>
                    
                    <a href="?eliminar=<?= $tarea['id'] ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('¿Eliminar esta tarea?')">Eliminar</a>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
```

---

## Mejores prácticas

### ✅ Hacer

#### 1. Siempre validar datos en el servidor

```php
<?php
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];  // ✅ Convertir a entero
    
    if ($id <= 0) {  // ✅ Validar
        die("ID inválido");
    }
    
    // Procesar
}
?>
```

#### 2. Redirigir después de procesar

```php
<?php
if (isset($_GET['eliminar'])) {
    eliminarRegistro($_GET['eliminar']);
    header("Location: " . $_SERVER['PHP_SELF']);  // ✅
    exit();
}
?>
```

#### 3. Usar confirmaciones JavaScript

```html
<a href="?eliminar=5" onclick="return confirm('¿Seguro?')">Eliminar</a>
```

#### 4. Escapar salida HTML

```php
<td><?= htmlspecialchars($nombre) ?></td>
<a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?id=5">Link</a>
```

#### 5. Usar POST para operaciones destructivas (producción)

```html
<form method="POST">
    <button type="submit" name="eliminar" value="5">Eliminar</button>
</form>
```

#### 6. Implementar tokens CSRF para POST

```php
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
```

---

### ❌ Evitar

#### 1. No validar datos del usuario

```php
<?php
// ❌ Peligroso
$id = $_GET['eliminar'];
$pdo->query("DELETE FROM tabla WHERE id = $id");
?>
```

#### 2. No redirigir después de procesar

```php
<?php
// ❌ La URL queda con ?eliminar=5
if (isset($_GET['eliminar'])) {
    eliminarRegistro($_GET['eliminar']);
    // No redirige
}
?>
```

#### 3. No usar confirmación para eliminaciones

```html
<!-- ❌ Sin confirmación -->
<a href="?eliminar=5">Eliminar</a>
```

#### 4. Usar GET para operaciones destructivas en producción

```html
<!-- ⚠️ Solo para desarrollo/aprendizaje -->
<a href="?eliminar=5">Eliminar</a>
```

#### 5. No escapar `$_SERVER['PHP_SELF']`

```php
<!-- ❌ Vulnerable a XSS -->
<a href="<?= $_SERVER['PHP_SELF'] ?>?id=5">
```

---

## Resumen

### Enlaces `<a>`

```html
<!-- SIEMPRE hacen GET -->
<a href="pagina.php?parametro=valor">Enlace</a>
```

**Para POST, necesitas un formulario:**
```html
<form method="POST">
    <button>POST</button>
</form>
```

---

### Navegación a la misma página

```php
<!-- Enlace que navega a la misma página -->
<a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?accion=eliminar&id=<?= $id ?>">
    Eliminar
</a>

<?php
// Procesar en la misma página
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    $id = (int)$_GET['id'];
    eliminarRegistro($id);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
```

---

### Operaciones destructivas

| Método | Desarrollo | Producción |
|--------|-----------|-----------|
| **GET** | ✅ Aceptable (simple) | ⚠️ No recomendado |
| **POST** | ✅ Mejor práctica | ✅ Obligatorio |

**GET para desarrollo:**
```html
<a href="?eliminar=5" onclick="return confirm('¿Seguro?')">Eliminar</a>
```

**POST para producción:**
```html
<form method="POST" onsubmit="return confirm('¿Seguro?')">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    <button name="eliminar" value="5">Eliminar</button>
</form>
```

---

## Caché del navegador y navegación hacia atrás (BFCache)

### El problema

Cuando navegas hacia atrás (o hacia adelante) con las flechas del navegador, **el script PHP no se ejecuta**. Las variables de sesión no se actualizan, los contadores no cambian, y la página que ves puede estar desactualizada.

```
 Usuario pulsa ← Atrás
         │
         ▼
  Navegador consulta su BFCache
         │
   ¿Tiene la página guardada?  ──Sí──►  Muestra la copia en memoria
         │                               (PHP nunca se ejecuta)
         No
         │
         ▼
  Petición HTTP al servidor  ──────────►  PHP ejecuta el script
```

### Qué es el BFCache

El **Back-Forward Cache** (BFCache) es una optimización de los navegadores modernos (Chrome, Firefox, Safari) que guarda una snapshot completa de la página en memoria RAM cuando el usuario navega fuera de ella. Al volver, restaura esa snapshot al instante — sin hacer ninguna petición HTTP.

Esto es intencionado: hace la navegación mucho más rápida. El problema es que en aplicaciones PHP donde cada carga tiene efectos (incrementar un contador, registrar una visita, comprobar sesión…), el servidor nunca se entera de que el usuario volvió.

### Ejemplo concreto

```php
<?php
session_start();
// Este código NO se ejecuta al volver con ← Atrás
$_SESSION["count"] = isset($_SESSION["count"]) ? $_SESSION["count"] + 1 : 0;
?>
<p>Visitas: <?= $_SESSION["count"] ?></p>
<a href="otra.php">Ir a otra página</a>
```

Flujo:
```
1. Usuario carga la página                 → count = 0
2. Usuario hace clic en "Ir a otra página" → PHP ejecuta, count = 1
3. Usuario pulsa ← Atrás                  → BFCache: muestra "count = 0"
                                             PHP NO ejecuta, count sigue en 1 en sesión
4. Usuario recarga (F5)                    → PHP ejecuta, count = 2
```

### Cómo forzar que el servidor responda siempre (deshabilitar caché)

Se puede indicar al navegador que no guarde la página en caché mediante cabeceras HTTP. Hay que enviarlas **antes de cualquier salida**:

```php
<?php
// Deshabilitar toda caché para esta página
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
$_SESSION["count"] = isset($_SESSION["count"]) ? $_SESSION["count"] + 1 : 0;
?>
```

Con estas cabeceras, el navegador ignora el BFCache para esta página y siempre hace una petición nueva al servidor.

### Cuándo deshabilitarlo y cuándo no

| Situación | ¿Deshabilitar caché? | Motivo |
|-----------|---------------------|--------|
| Contador de visitas / sesión activa | ✅ Sí | El estado cambia en cada carga |
| Panel de admin / datos en tiempo real | ✅ Sí | Los datos deben estar actualizados |
| Página de logout | ✅ Sí | Volver atrás no debe restaurar la sesión |
| Página estática o de contenido fijo | ❌ No | La caché mejora el rendimiento |
| Formulario ya enviado (POST) | — | El navegador ya pregunta antes de reenviar |

### Nota sobre POST y navegación hacia atrás

Con formularios `POST` el comportamiento es distinto: el navegador **sí avisa** antes de reenviar los datos (muestra un diálogo *"¿Deseas volver a enviar el formulario?"*). Esto no ocurre con GET porque GET se considera una operación de solo lectura y el navegador la cachea libremente.

---

## Referencias

- [Métodos HTTP](https://developer.mozilla.org/es/docs/Web/HTTP/Methods)
- [Formularios HTML](https://developer.mozilla.org/es/docs/Learn/Forms)
- [CSRF Protection](https://owasp.org/www-community/attacks/csrf)
- [Back-Forward Cache (BFCache) - web.dev](https://web.dev/bfcache/)
