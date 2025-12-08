# Arrays en PHP

## Índice
1. [Copia de arrays](#copia-de-arrays)
2. [Referencias en arrays](#referencias-en-arrays)
3. [Eliminar elementos de un array](#eliminar-elementos-de-un-array)
4. [Comparación de métodos](#comparación-de-métodos)
5. [Ejemplos prácticos](#ejemplos-prácticos)
6. [Mejores prácticas](#mejores-prácticas)

---

## Copia de arrays

### ¿Se hace una copia real o no?

En PHP, cuando asignas un array a otra variable, se hace una **copia real** (copia por valor), **NO una referencia**.

```php
<?php
$original = [1, 2, 3];
$copia = $original;  // Se crea una copia REAL

$copia[0] = 999;     // Modificar la copia

echo $original[0];   // 1 (no cambió)
echo $copia[0];      // 999 (solo cambió la copia)
?>
```

**Resultado:**
```
Array original: [1, 2, 3]
Array copia:    [999, 2, 3]
```

---

### Comportamiento de copia por valor

PHP utiliza una técnica llamada **"copy-on-write"** (copiar al escribir):

```php
<?php
$array1 = [1, 2, 3, 4, 5];
$array2 = $array1;  // No se copia inmediatamente en memoria

// Mientras no modifiques, ambos apuntan a los mismos datos
// (optimización de memoria)

$array2[0] = 999;   // AHORA se hace la copia real

// A partir de aquí, son arrays completamente independientes
echo $array1[0];    // 1
echo $array2[0];    // 999
?>
```

**Diagrama:**
```
Antes de modificar:
$array1 ──┐
          ├──→ [1, 2, 3, 4, 5]
$array2 ──┘

Después de modificar $array2:
$array1 ──→ [1, 2, 3, 4, 5]
$array2 ──→ [999, 2, 3, 4, 5]
```

---

### Ejemplo detallado

```php
<?php
// Array original
$frutas = ['manzana', 'pera', 'naranja'];

// Hacer una copia
$frutas_copia = $frutas;

// Modificar la copia
$frutas_copia[0] = 'plátano';
$frutas_copia[] = 'sandía';  // Añadir elemento

// Ver resultados
print_r($frutas);
// Array ( [0] => manzana [1] => pera [2] => naranja )

print_r($frutas_copia);
// Array ( [0] => plátano [1] => pera [2] => naranja [3] => sandía )
?>
```

**Conclusión:** Son arrays completamente independientes.

---

### Copia de arrays multidimensionales

```php
<?php
$usuarios = [
    ['nombre' => 'Juan', 'edad' => 25],
    ['nombre' => 'María', 'edad' => 30]
];

$usuarios_copia = $usuarios;

// Modificar la copia
$usuarios_copia[0]['nombre'] = 'Pedro';

echo $usuarios[0]['nombre'];        // Juan (no cambió)
echo $usuarios_copia[0]['nombre'];  // Pedro
?>
```

**La copia es profunda (deep copy)**: Se copian todos los niveles del array.

---

### ¿Cuándo NO se hace copia?

Solo cuando usas **referencias explícitas** (operador `&`), que veremos en la siguiente sección.

```php
<?php
$original = [1, 2, 3];
$referencia = &$original;  // ← Referencia, NO copia

$referencia[0] = 999;

echo $original[0];    // 999 (SÍ cambió)
echo $referencia[0];  // 999
?>
```

---

## Referencias en arrays

### Operador de referencia (&)

El operador `&` hace que una variable sea una **referencia** a otra, no una copia. Ambas variables apuntan a los **mismos datos en memoria**.

---

### Sintaxis básica

```php
<?php
$array1 = [1, 2, 3];
$array2 = &$array1;  // ← $array2 es una REFERENCIA a $array1

$array2[0] = 999;

echo $array1[0];  // 999 (cambió)
echo $array2[0];  // 999 (cambió)
?>
```

**Diagrama:**
```
$array1 ──┐
          ├──→ [999, 2, 3]
$array2 ──┘
(Ambas variables apuntan al mismo array en memoria)
```

---

### Diferencia visual: Copia vs Referencia

```php
<?php
// ========== COPIA (sin &) ==========
$a = [1, 2, 3];
$b = $a;         // Copia

$b[0] = 999;

echo $a[0];      // 1 (original no cambió)
echo $b[0];      // 999 (solo la copia cambió)


// ========== REFERENCIA (con &) ==========
$x = [1, 2, 3];
$y = &$x;        // Referencia

$y[0] = 999;

echo $x[0];      // 999 (SÍ cambió)
echo $y[0];      // 999 (ambas cambian)
?>
```

---

### Pasar arrays por referencia a funciones

#### Sin referencia (por valor - copia):

```php
<?php
function modificarArray($array) {
    $array[0] = 999;  // Modifica la copia local
}

$numeros = [1, 2, 3];
modificarArray($numeros);

print_r($numeros);  // [1, 2, 3] (no cambió)
?>
```

#### Con referencia (por referencia):

```php
<?php
function modificarArray(&$array) {  // ← Referencia con &
    $array[0] = 999;  // Modifica el array original
}

$numeros = [1, 2, 3];
modificarArray($numeros);

print_r($numeros);  // [999, 2, 3] (SÍ cambió)
?>
```

---

### Referencias en foreach

#### Sin referencia (copia):

```php
<?php
$numeros = [1, 2, 3];

foreach ($numeros as $numero) {
    $numero = $numero * 2;  // Modifica la copia local
}

print_r($numeros);  // [1, 2, 3] (no cambió)
?>
```

#### Con referencia:

```php
<?php
$numeros = [1, 2, 3];

foreach ($numeros as &$numero) {  // ← Referencia con &
    $numero = $numero * 2;  // Modifica el elemento original
}

print_r($numeros);  // [2, 4, 6] (SÍ cambió)

// ⚠️ IMPORTANTE: Romper la referencia después del foreach
unset($numero);
?>
```

---

### ⚠️ Peligro: Referencias persistentes en foreach

```php
<?php
$array = [1, 2, 3];

foreach ($array as &$valor) {
    $valor *= 2;
}
// $valor todavía es una referencia a $array[2]

// Si usas $valor después, puede causar problemas
foreach ($array as $valor) {  // ← Reutiliza $valor sin querer
    echo $valor;
}

print_r($array);  // [2, 4, 4] ← ¡Comportamiento inesperado!
?>
```

**Solución: Siempre usar `unset()` después:**

```php
<?php
$array = [1, 2, 3];

foreach ($array as &$valor) {
    $valor *= 2;
}
unset($valor);  // ✅ Romper la referencia

foreach ($array as $valor) {
    echo $valor;
}

print_r($array);  // [2, 4, 6] ✅ Correcto
?>
```

---

### Cuándo usar referencias

**✅ Usar referencias cuando:**
- Necesitas modificar el array original dentro de una función
- Trabajas con arrays muy grandes (para ahorrar memoria)
- Quieres que múltiples variables apunten a los mismos datos

**❌ Evitar referencias cuando:**
- No necesitas modificar el original
- Puede causar confusión en el código
- Trabajas con arrays pequeños (la optimización es mínima)

---

### Ejemplo práctico: Incrementar todos los valores

```php
<?php
function incrementarValores(&$array) {
    foreach ($array as &$valor) {
        $valor++;
    }
    unset($valor);  // Romper referencia
}

$numeros = [10, 20, 30];
incrementarValores($numeros);

print_r($numeros);  // [11, 21, 31]
?>
```

---

## Eliminar elementos de un array

### Método 1: `unset()` - Eliminar por índice

```php
<?php
$frutas = ['manzana', 'pera', 'naranja', 'plátano'];

unset($frutas[1]);  // Elimina 'pera'

print_r($frutas);
// Array ( [0] => manzana [2] => naranja [3] => plátano )
?>
```

**⚠️ Importante:** `unset()` **NO reindexa los índices**. El elemento en posición 1 desaparece, pero los índices 2 y 3 permanecen.

---

### Reindexar después de unset()

```php
<?php
$frutas = ['manzana', 'pera', 'naranja', 'plátano'];

unset($frutas[1]);

// Reindexar con array_values()
$frutas = array_values($frutas);

print_r($frutas);
// Array ( [0] => manzana [1] => naranja [2] => plátano )
?>
```

---

### Método 2: `array_splice()` - Eliminar y reindexar automáticamente

```php
<?php
$frutas = ['manzana', 'pera', 'naranja', 'plátano'];

// array_splice(array, inicio, cantidad)
array_splice($frutas, 1, 1);  // Elimina 1 elemento desde índice 1

print_r($frutas);
// Array ( [0] => manzana [1] => naranja [2] => plátano )
?>
```

**Ventaja:** Reindexación automática.

---

### Método 3: `array_filter()` - Eliminar por condición

```php
<?php
$numeros = [1, 2, 3, 4, 5, 6];

// Eliminar números pares
$impares = array_filter($numeros, function($n) {
    return $n % 2 !== 0;
});

print_r($impares);
// Array ( [0] => 1 [2] => 3 [4] => 5 )

// Reindexar si es necesario
$impares = array_values($impares);
// Array ( [0] => 1 [1] => 3 [2] => 5 )
?>
```

---

### Método 4: `array_diff()` - Eliminar por valor

```php
<?php
$frutas = ['manzana', 'pera', 'naranja', 'pera', 'plátano'];

// Eliminar todas las 'pera'
$frutas = array_diff($frutas, ['pera']);

print_r($frutas);
// Array ( [0] => manzana [2] => naranja [4] => plátano )

// Reindexar
$frutas = array_values($frutas);
// Array ( [0] => manzana [1] => naranja [2] => plátano )
?>
```

---

### Método 5: `array_pop()` y `array_shift()` - Eliminar primero/último

```php
<?php
$frutas = ['manzana', 'pera', 'naranja'];

// Eliminar último elemento
$ultimo = array_pop($frutas);
echo $ultimo;  // 'naranja'
print_r($frutas);  // ['manzana', 'pera']

// Eliminar primer elemento
$primero = array_shift($frutas);
echo $primero;  // 'manzana'
print_r($frutas);  // ['pera']
?>
```

**Ventaja:** Reindexan automáticamente y devuelven el elemento eliminado.

---

### Eliminar elementos de arrays asociativos

```php
<?php
$usuario = [
    'nombre' => 'Juan',
    'email' => 'juan@example.com',
    'edad' => 25,
    'ciudad' => 'Madrid'
];

// Eliminar clave específica
unset($usuario['edad']);

print_r($usuario);
// Array (
//     [nombre] => Juan
//     [email] => juan@example.com
//     [ciudad] => Madrid
// )
?>
```

**Nota:** En arrays asociativos, `unset()` es el método más común y no necesitas reindexar.

---

### Eliminar múltiples elementos

```php
<?php
$numeros = [1, 2, 3, 4, 5, 6, 7, 8];

// Eliminar varios índices
unset($numeros[1], $numeros[3], $numeros[5]);

print_r($numeros);
// Array ( [0] => 1 [2] => 3 [4] => 5 [6] => 7 [7] => 8 )

// Reindexar
$numeros = array_values($numeros);
// Array ( [0] => 1 [1] => 3 [2] => 5 [3] => 7 [4] => 8 )
?>
```

---

### Vaciar completamente un array

```php
<?php
$frutas = ['manzana', 'pera', 'naranja'];

// Método 1: Asignar array vacío
$frutas = [];

// Método 2: unset() en toda la variable
unset($frutas);

// Método 3: array_splice() de todo
array_splice($frutas, 0);
?>
```

---

## Comparación de métodos

### Tabla comparativa: Eliminar elementos

| Método | Reindexación | Uso típico | Retorna elemento |
|--------|--------------|------------|------------------|
| `unset($array[$i])` | ❌ No | Eliminar por índice/clave | ❌ No |
| `array_splice()` | ✅ Sí | Eliminar por posición | ✅ Sí (array eliminado) |
| `array_filter()` | ❌ No | Eliminar por condición | ❌ No |
| `array_diff()` | ❌ No | Eliminar por valores específicos | ❌ No |
| `array_pop()` | ✅ Sí | Eliminar último | ✅ Sí |
| `array_shift()` | ✅ Sí | Eliminar primero | ✅ Sí |

---

### Tabla comparativa: Copia vs Referencia

| Característica | Copia (`=`) | Referencia (`&`) |
|----------------|-------------|------------------|
| **Sintaxis** | `$b = $a` | `$b = &$a` |
| **Memoria** | Datos duplicados (copy-on-write) | Mismo espacio en memoria |
| **Modificación** | Independientes | Ambas variables cambian |
| **Uso recomendado** | Por defecto | Solo cuando sea necesario |
| **En funciones** | `function($arr)` | `function(&$arr)` |
| **En foreach** | `foreach ($arr as $val)` | `foreach ($arr as &$val)` |

---

## Ejemplos prácticos

### Ejemplo 1: Eliminar elementos vacíos de un array

```php
<?php
$datos = ['Juan', '', 'María', null, 'Pedro', 0, false, 'Ana'];

// Eliminar valores "vacíos" (vacíos, null, false, 0)
$datos_limpios = array_filter($datos);

print_r($datos_limpios);
// Array ( [0] => Juan [2] => María [4] => Pedro [7] => Ana )

// Reindexar
$datos_limpios = array_values($datos_limpios);
// Array ( [0] => Juan [1] => María [2] => Pedro [3] => Ana )
?>
```

---

### Ejemplo 2: Eliminar duplicados

```php
<?php
$numeros = [1, 2, 3, 2, 4, 1, 5, 3];

// Eliminar duplicados
$unicos = array_unique($numeros);

print_r($unicos);
// Array ( [0] => 1 [1] => 2 [2] => 3 [4] => 4 [6] => 5 )

// Reindexar
$unicos = array_values($unicos);
// Array ( [0] => 1 [1] => 2 [2] => 3 [3] => 4 [4] => 5 )
?>
```

---

### Ejemplo 3: Modificar array dentro de función (con referencia)

```php
<?php
function eliminarNegativos(&$numeros) {
    $numeros = array_filter($numeros, function($n) {
        return $n >= 0;
    });
    $numeros = array_values($numeros);  // Reindexar
}

$valores = [5, -3, 8, -1, 2, -7, 10];
eliminarNegativos($valores);

print_r($valores);
// Array ( [0] => 5 [1] => 8 [2] => 2 [3] => 10 )
?>
```

---

### Ejemplo 4: Clonar arrays para comparación

```php
<?php
$original = [1, 2, 3, 4, 5];

// Hacer una copia para procesar
$procesado = $original;

// Eliminar números pares de la copia
$procesado = array_filter($procesado, function($n) {
    return $n % 2 !== 0;
});
$procesado = array_values($procesado);

// Comparar
echo "Original: ";
print_r($original);   // [1, 2, 3, 4, 5]

echo "Procesado: ";
print_r($procesado);  // [1, 3, 5]
?>
```

---

### Ejemplo 5: Actualizar valores con referencia en foreach

```php
<?php
$productos = [
    ['nombre' => 'Laptop', 'precio' => 1000],
    ['nombre' => 'Mouse', 'precio' => 20],
    ['nombre' => 'Teclado', 'precio' => 50]
];

// Aplicar descuento del 10%
foreach ($productos as &$producto) {
    $producto['precio'] *= 0.9;
}
unset($producto);  // Romper referencia

print_r($productos);
// Array (
//     [0] => Array ( [nombre] => Laptop [precio] => 900 )
//     [1] => Array ( [nombre] => Mouse [precio] => 18 )
//     [2] => Array ( [nombre] => Teclado [precio] => 45 )
// )
?>
```

---

### Ejemplo 6: Eliminar usuario de array por ID

```php
<?php
$usuarios = [
    ['id' => 1, 'nombre' => 'Juan'],
    ['id' => 2, 'nombre' => 'María'],
    ['id' => 3, 'nombre' => 'Pedro'],
    ['id' => 4, 'nombre' => 'Ana']
];

$id_eliminar = 2;

// Método 1: Con array_filter
$usuarios = array_filter($usuarios, function($usuario) use ($id_eliminar) {
    return $usuario['id'] !== $id_eliminar;
});
$usuarios = array_values($usuarios);

// Método 2: Con foreach y unset
foreach ($usuarios as $indice => $usuario) {
    if ($usuario['id'] == $id_eliminar) {
        unset($usuarios[$indice]);
        break;  // Detener después de encontrar
    }
}
$usuarios = array_values($usuarios);

print_r($usuarios);
// Array (
//     [0] => Array ( [id] => 1 [nombre] => Juan )
//     [1] => Array ( [id] => 3 [nombre] => Pedro )
//     [2] => Array ( [id] => 4 [nombre] => Ana )
// )
?>
```

---

## Mejores prácticas

### ✅ Hacer

#### 1. Usar copia por defecto (sin &)

```php
<?php
$original = [1, 2, 3];
$copia = $original;  // ✅ Copia segura por defecto
?>
```

#### 2. Usar referencias solo cuando sea necesario

```php
<?php
// ✅ BIEN - Necesitas modificar el original
function duplicarValores(&$array) {
    foreach ($array as &$valor) {
        $valor *= 2;
    }
    unset($valor);
}
?>
```

#### 3. Siempre usar unset() después de foreach con referencias

```php
<?php
foreach ($array as &$valor) {
    // ... modificaciones
}
unset($valor);  // ✅ Romper la referencia
?>
```

#### 4. Reindexar después de eliminar con unset()

```php
<?php
unset($array[2]);
$array = array_values($array);  // ✅ Reindexar
?>
```

#### 5. Usar array_splice() cuando necesites reindexación automática

```php
<?php
array_splice($array, 2, 1);  // ✅ Elimina y reindexa
?>
```

#### 6. Documentar cuándo una función modifica el array original

```php
<?php
/**
 * Elimina elementos negativos del array
 * @param array &$numeros Array que será modificado (por referencia)
 */
function eliminarNegativos(&$numeros) {
    // ...
}
?>
```

---

### ❌ Evitar

#### 1. Asumir que la asignación crea una referencia

```php
<?php
// ❌ MAL - Asume que son el mismo array
$array1 = [1, 2, 3];
$array2 = $array1;
$array2[0] = 999;
// $array1 NO cambió

// ✅ BIEN - Usar referencia explícita si es necesario
$array2 = &$array1;
?>
```

#### 2. Olvidar unset() después de foreach con referencia

```php
<?php
// ❌ PELIGRO - $valor sigue siendo una referencia
foreach ($array as &$valor) {
    $valor *= 2;
}
// Usar $valor aquí puede causar bugs

// ✅ BIEN
foreach ($array as &$valor) {
    $valor *= 2;
}
unset($valor);
?>
```

#### 3. No reindexar después de unset() cuando importa el orden

```php
<?php
// ❌ MAL - Índices desorganizados
unset($array[2]);
print_r($array);  // [0] => a, [1] => b, [3] => d

// ✅ BIEN - Reindexar
unset($array[2]);
$array = array_values($array);
print_r($array);  // [0] => a, [1] => b, [2] => d
?>
```

#### 4. Modificar array mientras iteras sobre él (sin referencia)

```php
<?php
// ❌ MAL - Los cambios no se reflejan
foreach ($array as $valor) {
    $valor = $valor * 2;  // Solo modifica la copia local
}

// ✅ BIEN - Usar referencia
foreach ($array as &$valor) {
    $valor = $valor * 2;
}
unset($valor);
?>
```

#### 5. Usar referencias cuando no es necesario

```php
<?php
// ❌ Innecesariamente complejo
function sumar($a, $b) {
    return $a + $b;
}
$resultado = &sumar(5, 3);  // No tiene sentido

// ✅ BIEN - Solo usar referencias cuando modificas
function duplicarArray(&$array) {
    foreach ($array as &$val) {
        $val *= 2;
    }
}
?>
```

---

## Resumen

### Copia de arrays

```php
// Por defecto, PHP hace COPIA (no referencia)
$copia = $original;  // Copia real
$copia[0] = 999;     // NO afecta a $original
```

**Copy-on-write:** PHP optimiza la memoria, pero cuando modificas, se hace una copia real.

---

### Referencias

```php
// Con &, creas una REFERENCIA (no copia)
$ref = &$original;   // Ambas apuntan al mismo array
$ref[0] = 999;       // SÍ afecta a $original

// En funciones
function modificar(&$arr) { }  // Modifica el original

// En foreach
foreach ($arr as &$val) { }    // Modifica elementos originales
unset($val);                    // ⚠️ SIEMPRE romper referencia
```

---

### Eliminar elementos

| Método | Cuándo usar |
|--------|-------------|
| `unset($arr[$i])` | Eliminar por índice (requiere reindexar) |
| `array_splice()` | Eliminar por posición (reindexa automático) |
| `array_filter()` | Eliminar por condición |
| `array_diff()` | Eliminar valores específicos |
| `array_pop()` | Eliminar último |
| `array_shift()` | Eliminar primero |

**Reindexar:** `$array = array_values($array);`

---

## Referencias

- [Documentación oficial de Arrays](https://www.php.net/manual/es/language.types.array.php)
- [Referencias en PHP](https://www.php.net/manual/es/language.references.php)
- [Funciones de arrays](https://www.php.net/manual/es/ref.array.php)
