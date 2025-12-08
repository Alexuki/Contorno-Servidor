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

### Método 2: Plantilla con función

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

---

### Método 4: Plantilla con datos estructurados

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
