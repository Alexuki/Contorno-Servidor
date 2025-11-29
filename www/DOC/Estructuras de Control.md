# Estructuras de Control en PHP con HTML

## Índice
1. [Cómo Funciona PHP con HTML](#cómo-funciona-php-con-html)
2. [Sintaxis con Llaves](#sintaxis-con-llaves)
3. [Sintaxis Alternativa](#sintaxis-alternativa)
4. [Comparación y Recomendaciones](#comparación-y-recomendaciones)
5. [Ejemplos Prácticos](#ejemplos-prácticos)

---

## Cómo Funciona PHP con HTML

### ¿Por qué funciona el código cuando se divide en bloques?

Cuando escribes código como este:

```php
<?php if ($edad >= 18) { ?>
    <p>Eres mayor de edad</p>
    <a href="acceso.php">Acceder</a>
<?php } ?>
```

**Parece que hay dos bloques PHP separados con HTML en medio**, pero en realidad **el bloque `if` nunca se cierra** hasta el segundo `<?php } ?>`.

### Explicación paso a paso:

PHP es un **lenguaje de plantillas** que funciona como **preprocesador**. Cuando el servidor procesa el archivo:

#### 1. PHP lee TODO el archivo secuencialmente

```php
<?php if ($edad >= 18) { ?>
    <p>Eres mayor de edad</p>
<?php } ?>
```

#### 2. PHP interpreta el código en orden:

1. **Encuentra `<?php`** → Entra en "modo PHP"
2. **Evalúa `if ($edad >= 18) {`** → Abre el bloque condicional
3. **Encuentra `?>`** → Sale de "modo PHP", **pero el bloque `if` sigue abierto**
4. **Lee el HTML** → Como el `if` es verdadero, **envía el HTML al output**
5. **Encuentra `<?php`** → Vuelve a "modo PHP"
6. **Evalúa `}`** → Cierra el bloque `if`

### La clave: El bloque `if` nunca se cerró

```php
<?php if ($edad >= 18) { 
    // La llave { está ABIERTA
    // PHP sale de modo código pero NO cierra el if
?>
    <!-- Este HTML está DENTRO del if, aunque no esté entre <?php ?> -->
    <p>Eres mayor de edad</p>
<?php 
    // PHP vuelve a modo código
    // El if SIGUE ABIERTO desde arriba
} // AQUÍ se cierra el if
?>
```

### Visualización mental

Piensa que PHP **traduce** el código así internamente:

```php
// Tu código:
<?php if ($edad >= 18) { ?>
    <p>Eres mayor de edad</p>
<?php } ?>

// PHP lo interpreta como:
<?php
if ($edad >= 18) {
    echo "    <p>Eres mayor de edad</p>\n";
}
?>
```

### Ejemplo más complejo

```php
<?php if ($edad >= 18) { ?>
    <p>Mayor de edad</p>
    <?php if ($premium) { ?>
        <span>Usuario Premium</span>
    <?php } ?>
    <a href="acceso.php">Acceder</a>
<?php } else { ?>
    <p>Menor de edad</p>
<?php } ?>
```

**PHP lo procesa como:**

```php
<?php
if ($edad >= 18) {
    echo "<p>Mayor de edad</p>\n";
    if ($premium) {
        echo "<span>Usuario Premium</span>\n";
    }
    echo "<a href=\"acceso.php\">Acceder</a>\n";
} else {
    echo "<p>Menor de edad</p>\n";
}
?>
```

### Reglas importantes

1. **`<?php` y `?>` solo cambian el "modo"** (código PHP vs output)
2. **Las estructuras de control (if, for, while) persisten** entre bloques PHP
3. **El HTML entre `?>` y `<?php` se envía al output** si la condición es verdadera
4. **Debes cerrar las llaves `{}` en un bloque PHP** (no pueden quedar abiertas sin volver a entrar en modo PHP)

### Ejemplos de uso válido e inválido

```php
✅ Válido:
<?php if ($x) { ?>
    HTML
<?php } ?>

✅ Válido:
<?php 
if ($x) { 
    echo "HTML";
}
?>

❌ Inválido (sintaxis rota):
<?php if ($x) { ?>
    HTML
<!-- Falta cerrar la llave } -->

❌ Error común:
<?php if ($x) { ?>
    <p>Texto</p>
<!-- Olvidé cerrar el } -->

<?php if ($y) { ?>  <!-- ¡Nuevo if sin cerrar el anterior! -->
    <p>Otro texto</p>
<?php } ?>
```

### Resumen

**PHP mantiene el contexto de las estructuras de control** aunque cambies entre modo PHP (`<?php`) y modo output (HTML). El `?>` solo significa "deja de interpretar como código y envía esto al navegador", pero **las llaves abiertas siguen activas**.

---

## Sintaxis con Llaves `{}`

### IF-ELSE con llaves

#### Código PHP puro:
```php
<?php
if ($condicion) {
    echo "Verdadero";
} else {
    echo "Falso";
}
?>
```

#### Mezclando PHP y HTML:
```php
<?php if ($edad >= 18) { ?>
    <p>Eres mayor de edad</p>
    <a href="acceso.php">Acceder</a>
<?php } else { ?>
    <p>Eres menor de edad</p>
    <a href="salir.php">Salir</a>
<?php } ?>
```

#### Anidado con HTML:
```php
<?php
$usuario = "admin";
$activo = true;

if ($usuario === "admin") { ?>
    <div class="panel-admin">
        <h2>Panel de Administración</h2>
        <?php if ($activo) { ?>
            <p class="text-success">Cuenta activa</p>
        <?php } else { ?>
            <p class="text-danger">Cuenta inactiva</p>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="panel-usuario">
        <h2>Panel de Usuario</h2>
    </div>
<?php } ?>
```

---

### SWITCH con llaves

#### Código PHP puro:
```php
<?php
switch ($dia) {
    case 1:
        echo "Lunes";
        break;
    case 2:
        echo "Martes";
        break;
    default:
        echo "Otro día";
}
?>
```

#### Mezclando PHP y HTML:
```php
<?php
$bebida = "Coca Cola";
switch ($bebida) {
    case "Coca Cola": ?>
        <div class="bebida">
            <h3>Coca Cola</h3>
            <p>Precio: 1.00€</p>
            <img src="coca-cola.jpg" alt="Coca Cola">
        </div>
    <?php break;
    
    case "Pepsi Cola": ?>
        <div class="bebida">
            <h3>Pepsi Cola</h3>
            <p>Precio: 0.80€</p>
            <img src="pepsi.jpg" alt="Pepsi">
        </div>
    <?php break;
    
    default: ?>
        <div class="bebida">
            <h3>Bebida no disponible</h3>
        </div>
    <?php break;
}
?>
```

NOTA: https://www.php.net/manual/en/control-structures.alternative-syntax.php

Any output (including whitespace) between a switch statement and the first case will result in a syntax error. For example, this is invalid:

```php
<?php switch ($foo): ?>
    <?php case 1: ?>
    // ...
<?php endswitch; ?>
```

Whereas this is valid, as the trailing newline after the switch statement is considered part of the closing ?> and hence nothing is output between the switch and case:

```php
<?php switch ($foo): ?>
<?php case 1: ?>
    ...
<?php endswitch; ?>
```

---

### FOR con llaves

```php
<?php for ($i = 1; $i <= 5; $i++) { ?>
    <div class="item">
        <h3>Item <?= $i ?></h3>
        <p>Contenido del item número <?= $i ?></p>
    </div>
<?php } ?>
```

---

### FOREACH con llaves

```php
<?php
$usuarios = ['Ana', 'Juan', 'Pedro'];
foreach ($usuarios as $usuario) { ?>
    <div class="card">
        <h4><?= $usuario ?></h4>
        <button>Ver perfil</button>
    </div>
<?php } ?>
```

---

## Sintaxis Alternativa

La sintaxis alternativa **reemplaza las llaves `{}`** por **dos puntos `:` y palabras clave de cierre**.

### Conversión de sintaxis:

| Con llaves | Alternativa |
|------------|-------------|
| `if (...) {` | `if (...):` |
| `}` | `endif;` |
| `else {` | `else:` |
| `elseif (...) {` | `elseif (...):` |
| `switch (...) {` | `switch (...):` |
| `}` | `endswitch;` |
| `for (...) {` | `for (...):` |
| `}` | `endfor;` |
| `foreach (...) {` | `foreach (...):` |
| `}` | `endforeach;` |
| `while (...) {` | `while (...):` |
| `}` | `endwhile;` |

---

### IF-ELSE alternativo

#### Ejemplo básico:
```php
<?php if ($edad >= 18): ?>
    <p>Eres mayor de edad</p>
<?php else: ?>
    <p>Eres menor de edad</p>
<?php endif; ?>
```

#### Con ELSEIF:
```php
<?php if ($nota >= 9): ?>
    <span class="badge bg-success">Sobresaliente</span>
<?php elseif ($nota >= 7): ?>
    <span class="badge bg-primary">Notable</span>
<?php elseif ($nota >= 5): ?>
    <span class="badge bg-info">Aprobado</span>
<?php else: ?>
    <span class="badge bg-danger">Suspenso</span>
<?php endif; ?>
```

#### Anidado:
```php
<?php if ($usuario_logueado): ?>
    <div class="usuario-panel">
        <h2>Bienvenido <?= $nombre ?></h2>
        
        <?php if ($es_premium): ?>
            <div class="premium-badge">
                <i class="icon-star"></i>
                <span>Usuario Premium</span>
            </div>
        <?php else: ?>
            <a href="upgrade.php" class="btn btn-warning">
                Hazte Premium
            </a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="login-form">
        <h2>Inicia sesión</h2>
        <form method="POST">
            <!-- formulario -->
        </form>
    </div>
<?php endif; ?>
```

---

### SWITCH alternativo

```php
<?php
$rol = "administrador";
switch ($rol):
    case "administrador": ?>
        <nav class="admin-nav">
            <a href="usuarios.php">Gestionar Usuarios</a>
            <a href="config.php">Configuración</a>
            <a href="reportes.php">Reportes</a>
        </nav>
    <?php break;
    
    case "editor": ?>
        <nav class="editor-nav">
            <a href="posts.php">Gestionar Posts</a>
            <a href="media.php">Multimedia</a>
        </nav>
    <?php break;
    
    case "usuario": ?>
        <nav class="user-nav">
            <a href="perfil.php">Mi Perfil</a>
            <a href="ajustes.php">Ajustes</a>
        </nav>
    <?php break;
    
    default: ?>
        <p>Rol no reconocido</p>
    <?php break;
endswitch;
?>
```

---

### FOR alternativo

```php
<?php for ($i = 1; $i <= 5; $i++): ?>
    <div class="col-md-4">
        <div class="card">
            <h3>Producto <?= $i ?></h3>
            <p>Descripción del producto</p>
            <button class="btn btn-primary">Comprar</button>
        </div>
    </div>
<?php endfor; ?>
```

---

### FOREACH alternativo

#### Array simple:
```php
<?php
$colores = ['Rojo', 'Verde', 'Azul'];
foreach ($colores as $color): ?>
    <span class="badge" style="background-color: <?= strtolower($color) ?>">
        <?= $color ?>
    </span>
<?php endforeach; ?>
```

#### Array asociativo:
```php
<?php
$productos = [
    'Laptop' => 899.99,
    'Mouse' => 29.99,
    'Teclado' => 59.99
];

foreach ($productos as $nombre => $precio): ?>
    <tr>
        <td><?= $nombre ?></td>
        <td><?= number_format($precio, 2) ?>€</td>
        <td>
            <button class="btn btn-sm btn-success">Añadir</button>
        </td>
    </tr>
<?php endforeach; ?>
```

#### Tabla completa con FOREACH:
```php
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
                <td><?= $usuario['nombre'] ?></td>
                <td><?= $usuario['email'] ?></td>
                <td>
                    <a href="editar.php?id=<?= $usuario['id'] ?>" class="btn btn-primary btn-sm">
                        Editar
                    </a>
                    <a href="borrar.php?id=<?= $usuario['id'] ?>" class="btn btn-danger btn-sm">
                        Borrar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

---

### WHILE alternativo

```php
<?php
$contador = 1;
while ($contador <= 5): ?>
    <div class="alert alert-info">
        Iteración número <?= $contador ?>
    </div>
    <?php $contador++; ?>
<?php endwhile; ?>
```

---

## Comparación y Recomendaciones

### Ventajas de cada sintaxis:

| Sintaxis con llaves `{}` | Sintaxis alternativa `:` |
|--------------------------|--------------------------|
| ✅ Más común en otros lenguajes | ✅ Más legible con HTML |
| ✅ Funciona en cualquier contexto | ✅ Cierre explícito (endif, endfor) |
| ✅ Familiar para programadores | ✅ Evita confusión de llaves |
| ❌ Puede ser difícil de leer con HTML | ❌ Solo para bloques con HTML |

---

### Cuándo usar cada una:

#### Usa llaves `{}` cuando:
```php
<?php
// Código PHP puro (sin HTML intermedio)
if ($x > 10) {
    $resultado = $x * 2;
    $mensaje = "Alto";
} else {
    $resultado = $x + 5;
    $mensaje = "Bajo";
}
?>
```

#### Usa sintaxis alternativa cuando:
```php
<?php if ($mostrar_banner): ?>
    <!-- HTML extenso -->
    <section class="banner">
        <div class="container">
            <h1>Título</h1>
            <p>Descripción larga...</p>
            <button>Acción</button>
        </div>
    </section>
<?php endif; ?>
```

---

## Ejemplos Prácticos

### Ejemplo 1: Sistema de mensajes

#### Con llaves:
```php
<?php
$mensajes = $_SESSION['mensajes'] ?? [];

if (!empty($mensajes)) { ?>
    <div class="alert-container">
        <?php foreach ($mensajes as $mensaje) { ?>
            <div class="alert alert-<?= $mensaje['tipo'] ?>">
                <?= $mensaje['texto'] ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>
```

#### Con sintaxis alternativa:
```php
<?php
$mensajes = $_SESSION['mensajes'] ?? [];

if (!empty($mensajes)): ?>
    <div class="alert-container">
        <?php foreach ($mensajes as $mensaje): ?>
            <div class="alert alert-<?= $mensaje['tipo'] ?>">
                <?= $mensaje['texto'] ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
```

---

### Ejemplo 2: Menú de navegación según rol

```php
<nav class="navbar">
    <ul class="nav-menu">
        <li><a href="index.php">Inicio</a></li>
        
        <?php if (isset($_SESSION['usuario'])): ?>
            <li><a href="perfil.php">Mi Perfil</a></li>
            
            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <li><a href="admin.php">Administración</a></li>
                <li><a href="usuarios.php">Usuarios</a></li>
            <?php endif; ?>
            
            <li><a href="logout.php">Cerrar Sesión</a></li>
        <?php else: ?>
            <li><a href="login.php">Iniciar Sesión</a></li>
            <li><a href="registro.php">Registrarse</a></li>
        <?php endif; ?>
    </ul>
</nav>
```

---

### Ejemplo 3: Tabla de productos con estados

```php
<table class="table">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto['nombre'] ?></td>
                <td><?= number_format($producto['precio'], 2) ?>€</td>
                <td><?= $producto['stock'] ?></td>
                <td>
                    <?php if ($producto['stock'] > 10): ?>
                        <span class="badge bg-success">Disponible</span>
                    <?php elseif ($producto['stock'] > 0): ?>
                        <span class="badge bg-warning">Pocas unidades</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Agotado</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

---

### Ejemplo 4: Formulario condicional

```php
<form method="POST" action="procesar.php">
    <div class="form-group">
        <label>Tipo de usuario</label>
        <select name="tipo" id="tipo" class="form-control">
            <option value="particular">Particular</option>
            <option value="empresa">Empresa</option>
        </select>
    </div>
    
    <?php if ($_POST['tipo'] ?? '' === 'empresa'): ?>
        <div class="form-group">
            <label>CIF</label>
            <input type="text" name="cif" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Razón Social</label>
            <input type="text" name="razon_social" class="form-control" required>
        </div>
    <?php else: ?>
        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="dni" class="form-control" required>
        </div>
    <?php endif; ?>
    
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>
```

---

## Buenas Prácticas

### ✅ Hacer:

1. **Ser consistente** en todo el proyecto
2. **Usar sintaxis alternativa** para bloques con mucho HTML
3. **Indentar correctamente** el HTML dentro de las estructuras
4. **Usar `<?=` para echo corto** cuando sea apropiado

```php
<?php if ($activo): ?>
    <p class="text-success"><?= $mensaje ?></p>
<?php endif; ?>
```

5. **Cerrar las etiquetas PHP** cuando se mezcla con HTML

### ❌ Evitar:

1. **Mezclar sintaxis** (llaves y alternativa en el mismo bloque)
```php
<?php if ($x): ?>  <!-- Alternativa -->
    <p>Texto</p>
<?php } ?>  <!-- ❌ Llaves -->
```

2. **Abrir PHP sin cerrar**
```php
<?php if ($x):
    echo "<p>Texto</p>";  <!-- ❌ Sin ?>
endif; ?>
```

3. **Estructuras complejas sin espacios**
```php
<?phpif($x):?><p><?=$y?></p><?phpendif;?>  <!-- ❌ Ilegible -->
```

---

## Resumen

- **Llaves `{}`**: Para código PHP puro o lógica compleja
- **Sintaxis alternativa `:` `endif;`**: Para plantillas con HTML
- Ambas son válidas, **elige la más legible** según el contexto
- **Mantén la consistencia** en tu proyecto

---

## Referencias

- [PHP Manual - Alternative syntax](https://www.php.net/manual/en/control-structures.alternative-syntax.php)
- [PHP Manual - Control Structures](https://www.php.net/manual/en/language.control-structures.php)