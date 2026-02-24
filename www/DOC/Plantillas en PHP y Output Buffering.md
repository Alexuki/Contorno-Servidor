# Plantillas en PHP y Output Buffering

## Índice
1. [¿Qué son las plantillas?](#qué-son-las-plantillas)
2. [Problema que resuelven](#problema-que-resuelven)
3. [Métodos para crear plantillas](#métodos-para-crear-plantillas)
4. [Output Buffering con ob_start()](#output-buffering-con-ob_start)
5. [Comparación de enfoques](#comparación-de-enfoques)
6. [Ejemplos prácticos](#ejemplos-prácticos)
7. [Mejores prácticas](#mejores-prácticas)

---

## ¿Qué son las plantillas?

Las **plantillas** (templates) son archivos que contienen la **estructura común** de tus páginas HTML. Permiten definir una vez el diseño base y reutilizarlo en todas las páginas.

### Antes (sin plantillas):

```php
<!-- pagina1.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Página 1</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Contenido de página 1</h1>
</body>
</html>
```

```php
<!-- pagina2.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Página 2</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Contenido de página 2</h1>
</body>
</html>
```

**Problema:** Si necesitas cambiar algo en el `<head>` o añadir un footer, debes editar **TODAS las páginas**.

---

### Después (con plantillas):

```php
<!-- header.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?= $titulo ?></title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
```

```php
<!-- footer.php -->
</body>
</html>
```

```php
<!-- pagina1.php -->
<?php 
$titulo = "Página 1";
include "header.php"; 
?>

<h1>Contenido de página 1</h1>

<?php include "footer.php"; ?>
```

```php
<!-- pagina2.php -->
<?php 
$titulo = "Página 2";
include "header.php"; 
?>

<h1>Contenido de página 2</h1>

<?php include "footer.php"; ?>
```

**Ventaja:** Cambias una vez en `header.php` o `footer.php` y se actualiza en **todas las páginas**.

---

## Problema que resuelven

### Sin plantillas:

❌ Código HTML duplicado en cada página  
❌ Difícil mantener consistencia visual  
❌ Cambios requieren editar múltiples archivos  
❌ Propenso a errores (olvidar actualizar una página)  

### Con plantillas:

✅ HTML centralizado en un lugar  
✅ Fácil mantener diseño consistente  
✅ Cambios en un solo archivo  
✅ Menos errores  
✅ Código más limpio y organizado  

---

## Métodos para crear plantillas

### Comparación rápida de métodos

| Método | Complejidad | ¿Devuelve HTML? | Mejor para |
|--------|-------------|-----------------|------------|
| **1. Header/Footer includes** | ⭐ Simple | ❌ No, imprime directo | Proyectos pequeños |
| **2. Función con strings** | ⭐⭐ Media | ✅ Sí (concatenación) | Evitar (poco legible) |
| **3. Función con `ob_start()`** | ⭐⭐⭐ Media | ✅ Sí (captura HTML) | HTML complejo como variable |
| **4. Función que imprime** | ⭐ Simple | ❌ No, imprime directo | **RECOMENDADO para plantillas** |

---

### Método 1: Header y Footer (Más simple)

**Estructura:**
```
proyecto/
├── plantillas/
│   ├── header.php
│   └── footer.php
├── pagina1.php
└── pagina2.php
```

**plantillas/header.php:**
```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'Mi Sitio' ?></title>
    <link href="estilos.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="contacto.php">Contacto</a>
        </nav>
    </header>
    <main>
```

**plantillas/footer.php:**
```php
    </main>
    <footer>
        <p>&copy; 2025 Mi Sitio Web</p>
    </footer>
</body>
</html>
```

**Uso en pagina1.php:**
```php
<?php
// Lógica de negocio
$datos = obtenerDatos();
$titulo = "Mi Página 1";

// Incluir header
include "plantillas/header.php";
?>

<!-- Contenido específico de la página -->
<h1>Bienvenido a Página 1</h1>
<p>Este es el contenido único de esta página.</p>

<?php foreach ($datos as $dato): ?>
    <p><?= $dato ?></p>
<?php endforeach; ?>

<?php include "plantillas/footer.php"; ?>
```

**✅ Ventajas:**
- Muy simple de entender
- No requiere conceptos avanzados
- Fácil de implementar
- Ideal para proyectos pequeños

**❌ Desventajas:**
- Menos flexible
- Difícil pasar muchos datos a la plantilla

---

### Método 2: Función con strings (NO RECOMENDADO)

**plantillas/layout.php:**
```php
<?php
function renderizar_pagina($titulo, $contenido) {
    $html = '<!DOCTYPE html>';
    $html .= '<html><head>';
    $html .= '<title>' . $titulo . '</title>';
    $html .= '</head><body>';
    $html .= '<h1>' . $titulo . '</h1>';
    $html .= $contenido;
    $html .= '</body></html>';
    
    echo $html;
}
?>
```

**Uso:**
```php
<?php
include "plantillas/layout.php";

$contenido = "<p>Mi contenido</p>";
$contenido .= "<p>Más contenido</p>";

renderizar_pagina("Mi Página", $contenido);
?>
```

**❌ Problemas:**
- Concatenar strings es tedioso y propenso a errores
- Sintaxis HTML no tiene resaltado de código
- Difícil de mantener
- Propenso a errores de comillas y escape

**⚠️ NO USES ESTE MÉTODO** - Existe para completitud, pero hay mejores opciones.

---

### Método 3: Función con Output Buffering - Para capturar HTML en variables

**Cuándo usar:** Cuando necesitas que la función **devuelva** el HTML como string (para guardarlo en variable, procesarlo, etc.).

**plantillas/layout.php:**
```php
<?php
function renderizar_pagina($titulo, $contenido) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link href="estilos.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1><?= $titulo ?></h1>
    </header>
    
    <main>
        <?= $contenido ?>
    </main>
    
    <footer>
        <p>&copy; 2025 Mi Sitio</p>
    </footer>
</body>
</html>
<?php
}
?>
```

**Uso en pagina1.php:**
```php
<?php
include "plantillas/layout.php";

// Lógica de negocio
$datos = obtenerDatos();

// Preparar contenido como string
$contenido = "<h2>Mi Contenido</h2>";
$contenido .= "<p>Esto es una prueba</p>";

foreach ($datos as $dato) {
    $contenido .= "<p>$dato</p>";
}

// Renderizar
renderizar_pagina("Mi Página", $contenido);
?>
```

**⚠️ Problema:** Mezclar HTML con concatenación de strings es **incómodo y propenso a errores**.

---

### Método 3: Plantilla con Output Buffering (Avanzado)

Aquí es donde entra `ob_start()` para capturar HTML limpio.

**plantillas/layout.php:**
```php
<?php
function renderizar_pagina($titulo, $contenido) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link href="estilos.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1><?= $titulo ?></h1>
    </header>
    
    <main>
        <?= $contenido ?>
    </main>
    
    <footer>
        <p>&copy; 2025 Mi Sitio</p>
    </footer>
</body>
</html>
<?php
}
?>
```

**Uso en pagina1.php:**
```php
<?php
include "plantillas/layout.php";

// Lógica de negocio
$datos = obtenerDatos();

// Iniciar captura de output
ob_start();
?>

<!-- HTML limpio sin concatenar strings -->
<h2>Mi Contenido</h2>
<p>Esto es una prueba</p>

<?php foreach ($datos as $dato): ?>
    <p><?= $dato ?></p>
<?php endforeach; ?>

<div class="card">
    <h3>Título de card</h3>
    <p>Contenido de card</p>
</div>

<?php
// Obtener el contenido capturado
$contenido = ob_get_clean();

// Renderizar con la plantilla
renderizar_pagina("Mi Página", $contenido);
?>
```

**✅ Ventajas:**
- HTML limpio (no concatenas strings)
- Puedes usar toda la sintaxis de PHP/HTML
- Más flexible y escalable
- Separación clara entre lógica y presentación
- La función **devuelve** el HTML (puedes guardarlo en variable)

**⚠️ Cuándo usar:**
- Necesitas guardar el HTML en una variable
- Necesitas procesar o modificar el HTML antes de mostrarlo
- Trabajas con sistemas de cache

---

### Método 4: Función que imprime directamente (⭐ RECOMENDADO PARA PLANTILLAS)

Este es el método **más común y recomendado** para sistemas de plantillas en PHP.

**¿Por qué es el mejor para plantillas?**
- ✅ HTML limpio sin concatenación
- ✅ No requiere `ob_start()` (menos overhead)
- ✅ Sintaxis más natural para plantillas
- ✅ Es como funcionan WordPress, Laravel views, etc.

---

#### Estructura básica

**vista/pageTop.php:**
```php
<?php
function pageTop($titulo) {
    // No usa return, imprime directamente
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . "/header.php" ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include __DIR__ . "/menu.php" ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container pt-3 pb-2 mb-3 border-bottom">
                    <h2><?= $titulo ?></h2>
                </div>
                <div class="container">
                    <!-- Aquí empieza el contenido de cada página -->
<?php
}
?>
```

**vista/pageBottom.php:**
```php
<?php
function pageBottom() {
?>
                </div> <!-- Cierra container -->
            </main>
        </div> <!-- Cierra row -->
    </div> <!-- Cierra container-fluid -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}
?>
```

---

#### Uso en páginas

**tareas/lista_tareas.php:**
```php
<?php
// 1. Requires e includes al inicio
require_once "../modelo/pdo.php";
include_once "../vista/pageTop.php";
include_once "../vista/pageBottom.php";

// 2. Lógica de negocio
$conexion = conectarPDO();
$tareas = obtenerTareas($conexion);

// 3. Renderizar inicio de página
pageTop("Lista de Tareas");
?>

<!-- 4. HTML específico de esta página -->
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tareas as $tarea): ?>
        <tr>
            <td><?= $tarea['id'] ?></td>
            <td><?= $tarea['titulo'] ?></td>
            <td><?= $tarea['estado'] ?></td>
            <td>
                <a href="editar.php?id=<?= $tarea['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                <a href="borrar.php?id=<?= $tarea['id'] ?>" class="btn btn-sm btn-danger">Borrar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php 
// 5. Renderizar final de página
pageBottom(); 
?>
```

---

#### ⚠️ IMPORTANTE: Uso de `__DIR__` en includes

**Problema sin `__DIR__`:**

```php
// ❌ MAL - vista/pageTop.php
function pageTop($titulo) {
?>
    <?php include "header.php" ?>  <!-- Ruta relativa al CALLER -->
<?php
}
```

**¿Qué pasa?**

```
Estructura:
proyecto/
├── vista/
│   ├── pageTop.php
│   ├── header.php
│   └── menu.php
└── tareas/
    └── lista.php
```

```php
// tareas/lista.php
include_once "../vista/pageTop.php";
pageTop("Mis Tareas");

// Cuando pageTop() ejecuta: include "header.php"
// PHP busca en: tareas/header.php ❌ (no existe!)
// Error: Failed to open stream
```

---

**Solución con `__DIR__`:**

```php
// ✅ BIEN - vista/pageTop.php
function pageTop($titulo) {
?>
    <?php include __DIR__ . "/header.php" ?>  <!-- Ruta absoluta desde pageTop.php -->
<?php
}
```

**¿Cómo funciona `__DIR__`?**

```php
// vista/pageTop.php
function pageTop($titulo) {
    // __DIR__ = "/ruta/completa/al/proyecto/vista"
    
    include __DIR__ . "/header.php";
    // Resultado: "/ruta/completa/al/proyecto/vista/header.php" ✅
}
```

**Ahora funciona desde cualquier ubicación:**

```php
// tareas/lista.php (en cualquier carpeta)
include_once "../vista/pageTop.php";
pageTop("Mis Tareas");

// ✅ __DIR__ siempre apunta a 'vista/', sin importar desde dónde llames
```

---

#### Constantes mágicas de PHP

| Constante | Contiene | Ejemplo |
|-----------|----------|---------|
| `__DIR__` | Directorio del archivo actual | `/var/www/vista` |
| `__FILE__` | Ruta completa del archivo | `/var/www/vista/pageTop.php` |
| `__LINE__` | Número de línea actual | `15` |
| `__FUNCTION__` | Nombre de la función actual | `pageTop` |
| `__CLASS__` | Nombre de la clase actual | `MiClase` |
| `__METHOD__` | Nombre del método actual | `MiClase::miMetodo` |

---

#### Comparación: `include` vs `include_once`

```php
// include - Más rápido
include __DIR__ . "/header.php";
// ✅ Incluye siempre
// ✅ No verifica si ya fue incluido
// ✅ Más eficiente

// include_once - Más seguro
include_once __DIR__ . "/header.php";
// ✅ Verifica si ya fue incluido
// ✅ Previene inclusiones múltiples
// ⚠️ Ligeramente más lento
```

**Para funciones de plantilla:**
- Usa `include` porque solo se llama una vez por request
- O usa `include_once` si prefieres más seguridad (diferencia mínima)

---

#### Ejemplo completo con estructura de archivos

**Estructura:**
```
proyecto/
├── vista/
│   ├── pageTop.php      # Función pageTop()
│   ├── pageBottom.php   # Función pageBottom()
│   ├── header.php       # Barra de navegación
│   └── menu.php         # Menú lateral
├── modelo/
│   ├── pdo.php          # Funciones de BD con PDO
│   └── mysqli.php       # Funciones de BD con MySQLi
└── tareas/
    ├── lista.php
    ├── nueva.php
    └── editar.php
```

**vista/pageTop.php:**
```php
<?php
/**
 * Renderiza el inicio de la página HTML
 * @param string $titulo - Título de la página
 */
function pageTop($titulo) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD3 - <?= htmlspecialchars($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . "/header.php" ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include __DIR__ . "/menu.php" ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container pt-3 pb-2 mb-3 border-bottom">
                    <h2><?= htmlspecialchars($titulo) ?></h2>
                </div>
                <div class="container">
<?php
}
?>
```

**vista/pageBottom.php:**
```php
<?php
/**
 * Renderiza el final de la página HTML
 */
function pageBottom() {
?>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}
?>
```

**vista/header.php:**
```php
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="../index.php">
        UD3 - Anexo 2
    </a>
</header>
```

**vista/menu.php:**
```php
<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../tareas/lista.php">Lista de Tareas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../tareas/nueva.php">Nueva Tarea</a>
            </li>
        </ul>
    </div>
</nav>
```

---

#### Ventajas de este método (función que imprime)

| Ventaja | Descripción |
|---------|-------------|
| ✅ **HTML limpio** | No necesitas concatenar strings ni usar `ob_start()` |
| ✅ **Resaltado de sintaxis** | El editor colorea HTML correctamente |
| ✅ **Eficiente** | No hay overhead de buffering |
| ✅ **Natural** | Es como se usan las plantillas en frameworks reales |
| ✅ **Portable con `__DIR__`** | Funciona desde cualquier ubicación |
| ✅ **Fácil de mantener** | HTML legible y estructurado |

---

#### Cuándo usar cada método

| Método | Cuándo usar |
|--------|-------------|
| **Método 1: Header/Footer includes** | Proyectos muy simples sin funciones |
| **Método 2: Strings** | ❌ NUNCA (difícil de mantener) |
| **Método 3: `ob_start()` + return** | Necesitas el HTML en una variable para procesarlo |
| **Método 4: Función que imprime** | ⭐ **PLANTILLAS** (inicio y fin de páginas) |

---

### Método 5: Plantilla con datos estructurados

**plantillas/layout.php:**
```php
<?php
function renderizar_pagina($datos) {
    // Extraer variables del array
    extract($datos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link href="estilos.css" rel="stylesheet">
    <?= $css_extra ?? '' ?>
</head>
<body>
    <?php if ($mostrar_nav ?? true): ?>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="contacto.php">Contacto</a>
    </nav>
    <?php endif; ?>
    
    <main>
        <?= $contenido ?>
    </main>
    
    <footer>
        <p>&copy; 2025 Mi Sitio</p>
    </footer>
    
    <?= $js_extra ?? '' ?>
</body>
</html>
<?php
}
?>
```

**Uso en pagina1.php:**
```php
<?php
include "plantillas/layout.php";

$datos = obtenerDatos();

// Capturar contenido
ob_start();
?>

<h2>Mi Contenido</h2>

<?php foreach ($datos as $dato): ?>
    <p><?= $dato ?></p>
<?php endforeach; ?>

<?php
$contenido = ob_get_clean();

// Renderizar pasando array con todas las opciones
renderizar_pagina([
    'titulo' => 'Mi Página',
    'contenido' => $contenido,
    'mostrar_nav' => true,
    'css_extra' => '<link href="custom.css" rel="stylesheet">',
    'js_extra' => '<script src="custom.js"></script>'
]);
?>
```

---

## Output Buffering con ob_start()

### ¿Qué es Output Buffering?

Normalmente, cuando PHP ejecuta `echo` o imprime HTML, lo envía **inmediatamente al navegador**. El **output buffering** permite **capturar** esa salida en memoria antes de enviarla.

---

### Sin ob_start() (comportamiento normal)

```php
<?php
echo "Primera línea<br>";  // ← Se envía al navegador AHORA
echo "Segunda línea<br>";  // ← Se envía al navegador AHORA

// No puedes "recuperar" lo que ya se envió
?>
```

**Flujo:**
```
PHP → echo → Navegador (inmediatamente)
```

---

### Con ob_start() (captura en buffer)

```php
<?php
ob_start(); // ← Inicia la captura

echo "Primera línea<br>";  // ← NO se envía, va al buffer
echo "Segunda línea<br>";  // ← NO se envía, va al buffer

$contenido = ob_get_clean(); // ← Obtiene contenido y limpia buffer

// Ahora $contenido = "Primera línea<br>Segunda línea<br>"
// El navegador NO ha recibido nada todavía

echo $contenido; // ← AHORA sí se envía al navegador
?>
```

**Flujo:**
```
PHP → echo → Buffer (memoria) → ob_get_clean() → Variable → echo → Navegador
```

---

### Funciones de Output Buffering

| Función | Qué hace |
|---------|----------|
| `ob_start()` | Inicia la captura (abre el buffer) |
| `ob_get_contents()` | Obtiene el contenido del buffer (sin limpiar) |
| `ob_get_clean()` | Obtiene el contenido Y limpia el buffer |
| `ob_end_flush()` | Envía el contenido al navegador y cierra buffer |
| `ob_end_clean()` | Descarta el contenido y cierra buffer |
| `ob_get_length()` | Obtiene la longitud del buffer en bytes |

---

### Ejemplo paso a paso

```php
<?php
// 1. Iniciar captura
ob_start();

// 2. Todo esto se captura en el buffer
echo "Hola ";
echo "mundo";
?>
<p>Este es HTML</p>
<?php

// 3. Ver qué hay en el buffer (sin borrar)
$longitud = ob_get_length();
echo "Bytes en buffer: $longitud"; // También se captura esto

// 4. Obtener contenido y limpiar
$contenido = ob_get_clean();

// 5. Ahora puedes usar $contenido como variable
echo "ANTES<br>";
echo $contenido;
echo "<br>DESPUÉS";
?>
```

**Resultado en navegador:**
```
ANTES
Hola mundo
<p>Este es HTML</p>
Bytes en buffer: 35
DESPUÉS
```

---

### Ejemplo: Capturar HTML complejo

```php
<?php
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h3>Título de la Card</h3>
    </div>
    <div class="card-body">
        <p>Este es el contenido de la card.</p>
        <ul>
            <li>Item 1</li>
            <li>Item 2</li>
            <li>Item 3</li>
        </ul>
    </div>
    <div class="card-footer">
        <button>Aceptar</button>
    </div>
</div>

<?php
$html_card = ob_get_clean();

// Ahora puedes usar $html_card en cualquier parte
echo "<h1>Mi Página</h1>";
echo $html_card;
echo "<p>Más contenido después de la card</p>";
?>
```

---

### Ejemplo: Capturar contenido dinámico

```php
<?php
$usuarios = [
    ['nombre' => 'Juan', 'edad' => 25],
    ['nombre' => 'María', 'edad' => 30],
    ['nombre' => 'Pedro', 'edad' => 22]
];

// Capturar la tabla de usuarios
ob_start();
?>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Edad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['nombre'] ?></td>
            <td><?= $usuario['edad'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
$tabla_usuarios = ob_get_clean();

// Pasar a una función
renderizar_pagina("Usuarios", $tabla_usuarios);
?>
```

---

### ¿Cuándo usar ob_start()?

**✅ Usar cuando:**
- Necesitas capturar HTML complejo en una variable
- Quieres separar la generación de contenido de su renderizado
- Trabajas con sistemas de plantillas
- Necesitas procesar el HTML antes de enviarlo (ej: minificar)

**❌ NO usar cuando:**
- Puedes resolver el problema con includes simples
- Tu proyecto es pequeño y simple
- Añade complejidad innecesaria

---

### Ejemplo comparativo

#### Sin ob_start() (concatenación de strings):
```php
<?php
$html = "<div class='card'>";
$html .= "<h3>Título</h3>";
$html .= "<p>Contenido</p>";

foreach ($items as $item) {
    $html .= "<li>" . $item . "</li>";
}

$html .= "</div>";

echo $html;
?>
```

**Problemas:**
- ❌ Difícil de leer
- ❌ Propenso a errores de sintaxis (comillas, concatenación)
- ❌ Difícil mantener HTML complejo

---

#### Con ob_start() (HTML limpio):
```php
<?php
ob_start();
?>

<div class="card">
    <h3>Título</h3>
    <p>Contenido</p>
    
    <?php foreach ($items as $item): ?>
        <li><?= $item ?></li>
    <?php endforeach; ?>
</div>

<?php
$html = ob_get_clean();
echo $html;
?>
```

**Ventajas:**
- ✅ HTML limpio y legible
- ✅ Resaltado de sintaxis funciona
- ✅ Fácil de mantener
- ✅ Menos errores

---

## Comparación de enfoques

| Método | Complejidad | Flexibilidad | Ideal para |
|--------|-------------|--------------|------------|
| **Header/Footer** | ⭐ Muy simple | ⭐⭐ Limitada | Proyectos pequeños |
| **Función simple** | ⭐⭐ Simple | ⭐⭐ Media | Proyectos medianos |
| **ob_start()** | ⭐⭐⭐ Media | ⭐⭐⭐⭐ Alta | Proyectos grandes |
| **Sistema completo** | ⭐⭐⭐⭐ Compleja | ⭐⭐⭐⭐⭐ Muy alta | Aplicaciones complejas |

---

## Ejemplos prácticos

### Ejemplo completo: Sistema de plantillas simple

**Estructura de archivos:**
```
proyecto/
├── plantillas/
│   ├── header.php
│   ├── footer.php
│   └── layout.php
├── paginas/
│   ├── inicio.php
│   ├── productos.php
│   └── contacto.php
└── index.php
```

---

**plantillas/header.php:**
```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Mi Sitio Web' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Mi Sitio</a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="inicio.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="productos.php">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacto.php">Contacto</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container mt-4">
```

---

**plantillas/footer.php:**
```php
    </div>
    
    <footer class="bg-light text-center py-3 mt-5">
        <div class="container">
            <p>&copy; 2025 Mi Sitio Web. Todos los derechos reservados.</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

**paginas/inicio.php:**
```php
<?php
// Lógica de negocio
$titulo = "Inicio";
$noticias = [
    ['titulo' => 'Noticia 1', 'contenido' => 'Contenido de noticia 1'],
    ['titulo' => 'Noticia 2', 'contenido' => 'Contenido de noticia 2'],
    ['titulo' => 'Noticia 3', 'contenido' => 'Contenido de noticia 3']
];

// Incluir header
include "../plantillas/header.php";
?>

<!-- Contenido específico de la página -->
<h1>Bienvenido a Mi Sitio</h1>
<p>Esta es la página de inicio.</p>

<div class="row">
    <?php foreach ($noticias as $noticia): ?>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= $noticia['titulo'] ?></h5>
                <p class="card-text"><?= $noticia['contenido'] ?></p>
                <a href="#" class="btn btn-primary">Leer más</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include "../plantillas/footer.php"; ?>
```

---

**paginas/productos.php:**
```php
<?php
$titulo = "Productos";
$productos = obtenerProductos(); // Función que obtiene productos de BD

include "../plantillas/header.php";
?>

<h1>Nuestros Productos</h1>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
        <tr>
            <td><?= $producto['id'] ?></td>
            <td><?= $producto['nombre'] ?></td>
            <td>$<?= $producto['precio'] ?></td>
            <td><?= $producto['stock'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include "../plantillas/footer.php"; ?>
```

---

### Ejemplo completo: Con ob_start()

**plantillas/layout.php:**
```php
<?php
function renderizar_pagina($titulo, $contenido, $sidebar = null) {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Mi Sitio</a>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-<?= $sidebar ? '8' : '12' ?>">
                <h1><?= $titulo ?></h1>
                <?= $contenido ?>
            </div>
            
            <?php if ($sidebar): ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <?= $sidebar ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}
?>
```

---

**paginas/producto_detalle.php:**
```php
<?php
include "../plantillas/layout.php";

// Obtener producto
$producto = obtenerProductoPorId($_GET['id']);

// Capturar contenido principal
ob_start();
?>

<div class="card">
    <div class="card-body">
        <h2><?= $producto['nombre'] ?></h2>
        <p><?= $producto['descripcion'] ?></p>
        <p class="h3">Precio: $<?= $producto['precio'] ?></p>
        <button class="btn btn-primary">Añadir al carrito</button>
    </div>
</div>

<?php
$contenido_principal = ob_get_clean();

// Capturar sidebar
ob_start();
?>

<h5>Productos relacionados</h5>
<ul class="list-unstyled">
    <li><a href="#">Producto A</a></li>
    <li><a href="#">Producto B</a></li>
    <li><a href="#">Producto C</a></li>
</ul>

<?php
$contenido_sidebar = ob_get_clean();

// Renderizar todo
renderizar_pagina(
    $producto['nombre'], 
    $contenido_principal, 
    $contenido_sidebar
);
?>
```

---

## Mejores prácticas

### ✅ Hacer

#### 1. Usar variables para títulos y datos dinámicos
```php
<?php
$titulo = "Mi Página";
$datos = obtenerDatos();

include "header.php";
?>
```

#### 2. Mantener lógica separada de presentación
```php
<?php
// Toda la lógica primero
$usuarios = obtenerUsuarios();
$total = count($usuarios);

// Luego el HTML
include "header.php";
?>
<h1>Total: <?= $total ?></h1>
```

#### 3. Usar ob_start() para HTML complejo
```php
<?php
ob_start();
?>
<div class="complex-html">
    <!-- HTML complejo aquí -->
</div>
<?php
$html = ob_get_clean();
renderizar($html);
?>
```

#### 4. Cerrar siempre los buffers abiertos
```php
<?php
ob_start();
// ... código ...
$contenido = ob_get_clean(); // ✅ Cierra el buffer
?>
```

#### 5. Usar nombres descriptivos para variables de contenido
```php
<?php
$contenido_principal = ob_get_clean();
$sidebar_derecho = ob_get_clean();
$pie_de_pagina = ob_get_clean();
?>
```

---

### ❌ Evitar

#### 1. Mezclar HTML con concatenación de strings
```php
<?php
// ❌ MAL
$html = "<div class='card'>" . $titulo . "</div>";

// ✅ BIEN
ob_start();
?>
<div class="card"><?= $titulo ?></div>
<?php
$html = ob_get_clean();
?>
```

#### 2. Duplicar código HTML en múltiples páginas
```php
<?php
// ❌ MAL - Repetir <head> en cada página

// ✅ BIEN - Header compartido
include "header.php";
?>
```

#### 3. No cerrar buffers correctamente
```php
<?php
// ❌ MAL
ob_start();
echo "algo";
// No se cierra el buffer - puede causar problemas

// ✅ BIEN
ob_start();
echo "algo";
$contenido = ob_get_clean();
?>
```

#### 4. Usar ob_start() cuando no es necesario
```php
<?php
// ❌ Innecesariamente complejo
ob_start();
echo "Hola";
$saludo = ob_get_clean();
echo $saludo;

// ✅ Simple y directo
echo "Hola";
?>
```

#### 5. Olvidar htmlspecialchars() para datos del usuario
```php
<?php
// ❌ Vulnerable a XSS
echo $nombre_usuario;

// ✅ Seguro
echo htmlspecialchars($nombre_usuario);
?>
```

---

## Resumen

### Plantillas

| Método | Cuándo usar |
|--------|-------------|
| **Header/Footer** | Proyectos simples, aprendiendo PHP |
| **ob_start()** | Necesitas capturar HTML complejo |
| **Sistemas avanzados** | Aplicaciones grandes, equipos |

### Output Buffering

```php
ob_start();        // Inicia captura
// ... HTML ...
ob_get_clean();    // Obtiene y limpia
```

**Usa `ob_start()` cuando:**
- Necesitas HTML complejo en una variable
- Trabajas con sistemas de plantillas
- Quieres separar generación de renderizado

**NO uses `ob_start()` cuando:**
- Includes simples son suficientes
- Añade complejidad innecesaria

---

## Recursos adicionales

- [Documentación oficial de ob_start()](https://www.php.net/manual/es/function.ob-start.php)
- [Output Buffering en PHP](https://www.php.net/manual/es/book.outcontrol.php)
- Frameworks con plantillas: Laravel (Blade), Symfony (Twig), Smarty

---

## Conclusión

Las **plantillas** son esenciales para mantener código limpio y reutilizable. Empieza con **includes simples** (header/footer) y evoluciona a sistemas más complejos solo cuando lo necesites.

El **output buffering** con `ob_start()` es una herramienta poderosa para capturar HTML en variables, pero úsalo solo cuando aporte valor real a tu proyecto.

**Regla de oro:** Usa la solución más simple que resuelva tu problema.
