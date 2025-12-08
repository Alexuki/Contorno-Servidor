# Navegación y Operaciones CRUD con Enlaces

## Índice
1. [Enlaces `<a>` y métodos HTTP](#enlaces-a-y-métodos-http)
2. [Navegación a la misma página con GET](#navegación-a-la-misma-página-con-get)
3. [Operaciones destructivas con POST](#operaciones-destructivas-con-post)
4. [Confirmaciones y seguridad](#confirmaciones-y-seguridad)
5. [Ejemplos prácticos completos](#ejemplos-prácticos-completos)
6. [Mejores prácticas](#mejores-prácticas)

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

## Referencias

- [Métodos HTTP](https://developer.mozilla.org/es/docs/Web/HTTP/Methods)
- [Formularios HTML](https://developer.mozilla.org/es/docs/Learn/Forms)
- [CSRF Protection](https://owasp.org/www-community/attacks/csrf)
