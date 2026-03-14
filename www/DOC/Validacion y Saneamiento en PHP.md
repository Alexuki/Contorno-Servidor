# Validación y Saneamiento en PHP

## Índice
1. [Qué es validar y qué es sanear](#qué-es-validar-y-qué-es-sanear)
2. [Diferencia clave](#diferencia-clave)
3. [Funciones más usadas](#funciones-más-usadas)
4. [Constantes predefinidas (filtros)](#constantes-predefinidas-filtros)
5. [`$_POST` vs `INPUT_POST` (y otros)](#_post-vs-input_post-y-otros)
6. [Esquema de options y flags](#esquema-de-options-y-flags)
7. [Ejemplos prácticos](#ejemplos-prácticos)
8. [Buenas prácticas](#buenas-prácticas)

---

## Qué es validar y qué es sanear

### Validar
Validar significa **comprobar si un dato cumple una regla**.

Ejemplos:
- Un email tiene formato correcto.
- Una edad es un entero entre 0 y 120.
- Una URL es válida.

Si no cumple la regla, se rechaza.

### Sanear
Sanear (sanitizar) significa **limpiar o transformar un dato** para reducir riesgos o dejarlo en un formato seguro.

Ejemplos:
- Quitar etiquetas HTML de un texto.
- Limpiar caracteres no válidos de un email.
- Convertir caracteres especiales para mostrarlos en HTML.

---

## Diferencia clave

- **Validación:** responde a "¿este dato es correcto para esta regla?"
- **Saneamiento:** responde a "¿cómo limpio este dato para usarlo de forma segura?"

Regla práctica:
- Valida cuando el dato debe cumplir una condición concreta.
- Sanea cuando el dato puede venir sucio y quieres limpiarlo.
- En formularios reales, normalmente se hace **ambas cosas**.

---

## Funciones más usadas

### 1) `filter_var()`
Función principal para validar o sanear un valor con filtros de PHP.

```php
filter_var($valor, FILTER_VALIDATE_EMAIL);   // validar
filter_var($valor, FILTER_SANITIZE_EMAIL);   // sanear
```

### 2) `filter_input()`
Valida o sanea datos directamente desde superglobales (`INPUT_GET`, `INPUT_POST`, etc.).

```php
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
```

Aclaración importante:
- `filter_input()` no recibe el valor (`$_GET['campo']`) como primer parámetro.
- Recibe la **fuente** (`INPUT_GET`, `INPUT_POST`, ...) y el **nombre del campo**.
- Si ya tienes el valor en una variable, entonces se usa `filter_var()`.

```php
// Correcto con filter_input (fuente + nombre):
$edad1 = filter_input(INPUT_GET, 'edad', FILTER_VALIDATE_INT);

// Correcto con filter_var (valor ya leído):
$edadRaw = $_GET['edad'] ?? null;
$edad2 = filter_var($edadRaw, FILTER_VALIDATE_INT);
```

### 3) `trim()`
Elimina espacios al inicio y al final. Muy útil antes de validar.

```php
$nombre = trim($_POST['nombre'] ?? '');
```

### 4) `htmlspecialchars()`
Convierte caracteres especiales a entidades HTML para evitar XSS al mostrar datos en una página.

```php
echo htmlspecialchars($comentario, ENT_QUOTES, 'UTF-8');
```

### 5) `strip_tags()`
Quita etiquetas HTML de un texto (cuando no quieres permitir HTML).

```php
$textoPlano = strip_tags($textoUsuario);
```

---

## Constantes predefinidas (filtros)

PHP incluye constantes para usar con `filter_var()` y `filter_input()`.

### Filtros de validación
- `FILTER_VALIDATE_INT`
- `FILTER_VALIDATE_FLOAT`
- `FILTER_VALIDATE_BOOLEAN`
- `FILTER_VALIDATE_EMAIL`
- `FILTER_VALIDATE_URL`
- `FILTER_VALIDATE_IP`
- `FILTER_VALIDATE_REGEXP`

### Filtros de saneamiento
- `FILTER_SANITIZE_EMAIL`
- `FILTER_SANITIZE_URL`
- `FILTER_SANITIZE_NUMBER_INT`
- `FILTER_SANITIZE_NUMBER_FLOAT`
- `FILTER_SANITIZE_SPECIAL_CHARS`
- `FILTER_SANITIZE_FULL_SPECIAL_CHARS`

### Flags frecuentes (complementos)
- `FILTER_FLAG_ALLOW_FRACTION` (para flotantes con decimales)
- `FILTER_FLAG_ALLOW_THOUSAND` (permite separador de miles)
- `FILTER_FLAG_NO_ENCODE_QUOTES`
- `FILTER_NULL_ON_FAILURE`

---

## `$_POST` vs `INPUT_POST` (y otros)

Explicación sencilla:

- `$_POST` es un **array superglobal** de PHP.
- `INPUT_POST` **no es variable**, es una **constante predefinida** usada por `filter_input()`.

Por tanto:
- `$_POST`, `$_GET`, `$_COOKIE`, `$_SERVER`, etc. -> sí, son superglobales.
- `INPUT_POST`, `INPUT_GET`, `INPUT_COOKIE`, `INPUT_SERVER`, `INPUT_ENV` -> no son superglobales; son constantes para indicar el origen del dato.

### Diferencia práctica

Con `$_POST` accedes directamente al valor y luego lo validas/saneas tú.

```php
$email = $_POST['email'] ?? '';
$email = trim($email);
$esValido = filter_var($email, FILTER_VALIDATE_EMAIL);
```

Con `filter_input(..., INPUT_POST, ...)` pides a PHP que lea ese origen y aplique filtro en el mismo paso.

```php
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
```

### Más ejemplos

#### 1) `$_GET` vs `INPUT_GET`

```php
// Forma manual con superglobal:
$page = $_GET['page'] ?? '';
$page = filter_var($page, FILTER_VALIDATE_INT);

// Forma directa con filter_input:
$page2 = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
```

#### 2) `$_COOKIE` vs `INPUT_COOKIE`

```php
// Superglobal:
$tema = $_COOKIE['tema'] ?? 'claro';

// Con filtro:
$temaSeguro = filter_input(INPUT_COOKIE, 'tema', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
```

#### 3) `$_SERVER` vs `INPUT_SERVER`

```php
// Superglobal:
$ip = $_SERVER['REMOTE_ADDR'] ?? '';

// Con filtro de IP:
$ipValida = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
```

Nota importante:
- `$_POST` y compañía son arrays y puedes recorrerlos completos.
- `filter_input()` sirve para leer campos concretos de una fuente concreta de forma más declarativa.

---

## Esquema de options y flags

Sí, siguen un esquema y están predefinidas por PHP.

### Idea general

En `filter_var()` y `filter_input()` el 4º parámetro puede ser:
- Un entero de flags (combinable con `|`).
- Un array asociativo con claves predefinidas como `options` y/o `flags`.

```php
filter_var($valor, $filtro, $opciones);
filter_input(INPUT_POST, 'campo', $filtro, $opciones);
```

### Estructura típica del array

```php
[
    'options' => [
        // configuración específica del filtro
    ],
    'flags' => FILTER_FLAG_... | FILTER_FLAG_...
]
```

### ¿Qué está predefinido?

- Los nombres de filtros: `FILTER_VALIDATE_*`, `FILTER_SANITIZE_*`.
- Los flags: `FILTER_FLAG_*`.
- Las claves del array: `options` y `flags`.
- Algunas subclaves dentro de `options` según filtro, por ejemplo:
  - `min_range`, `max_range` en `FILTER_VALIDATE_INT`.
  - `default` para valor por defecto en ciertos casos.
  - `regexp` en `FILTER_VALIDATE_REGEXP`.

### Ejemplos de esquema

#### 1) Entero con rango (`options`)

```php
$edad = filter_var(
    '25',
    FILTER_VALIDATE_INT,
    [
        'options' => [
            'min_range' => 0,
            'max_range' => 120
        ]
    ]
);
```

#### 2) Regex (`options.regexp`)

```php
$usuario = filter_var(
    'alex_123',
    FILTER_VALIDATE_REGEXP,
    [
        'options' => [
            'regexp' => '/^[a-z0-9_]{4,20}$/i'
        ]
    ]
);
```

#### 3) URL con flags (`flags`)

```php
$url = filter_var(
    'https://example.com',
    FILTER_VALIDATE_URL,
    [
        'flags' => FILTER_FLAG_PATH_REQUIRED
    ]
);
```

#### 4) Combinar `options` y `flags`

```php
$numero = filter_var(
    '1,234.50',
    FILTER_SANITIZE_NUMBER_FLOAT,
    [
        'flags' => FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND
    ]
);
```

Nota:
- No todos los filtros aceptan las mismas opciones/flags.
- Si pasas una opción no compatible, normalmente se ignora o no tendrá efecto.

---

## Ejemplos prácticos

### Ejemplo 1: Email (sanear + validar)

```php
<?php
$emailRaw = $_POST['email'] ?? '';
$emailLimpio = filter_var(trim($emailRaw), FILTER_SANITIZE_EMAIL);

if (filter_var($emailLimpio, FILTER_VALIDATE_EMAIL)) {
    echo "Email válido: " . htmlspecialchars($emailLimpio, ENT_QUOTES, 'UTF-8');
} else {
    echo "Email no válido";
}
```

### Ejemplo 2: Edad entera en rango

```php
<?php
$edad = filter_input(
    INPUT_POST,
    'edad',
    FILTER_VALIDATE_INT,
    [
        'options' => [
            'min_range' => 0,
            'max_range' => 120
        ]
    ]
);

if ($edad !== false && $edad !== null) {
    echo "Edad válida: $edad";
} else {
    echo "Edad inválida";
}
```

### Ejemplo 3: Número decimal con flags

```php
<?php
$precio = filter_var(
    '1,234.50',
    FILTER_SANITIZE_NUMBER_FLOAT,
    FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND
);

echo $precio; // 1,234.50
```

### Ejemplo 4: Mostrar texto del usuario en HTML (prevención XSS)

```php
<?php
$comentario = $_POST['comentario'] ?? '';
$comentario = trim($comentario);

// Para mostrarlo en una vista HTML sin ejecutar etiquetas/scripts.
echo htmlspecialchars($comentario, ENT_QUOTES, 'UTF-8');
```

---

## Buenas prácticas

- No confíes en datos de `$_GET`, `$_POST`, `$_COOKIE`.
- Aplica `trim()` antes de validar muchos campos de texto.
- Valida reglas de negocio (rangos, formato, longitud).
- Sanea según el contexto de uso (HTML, URL, email, etc.).
- Para SQL, usa consultas preparadas (PDO/MySQLi), no solo saneamiento.
- Escapa salida con `htmlspecialchars()` al pintar datos en HTML.

En resumen:
- **Validar** = comprobar.
- **Sanear** = limpiar/transformar.
- En aplicaciones reales, se usan juntos.
