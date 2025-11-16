# Explicación de `date()` y `strtotime()` en PHP

## Funciones de Fecha en PHP

### `date()` - Formatear fechas

La función `date()` formatea una fecha/hora local según un formato especificado.

**Sintaxis:**
```php
date(string $format, ?int $timestamp = null): string
```

**Parámetros:**
- `$format`: String que especifica el formato de salida
- `$timestamp`: Timestamp Unix opcional (segundos desde 1 Enero 1970). Si se omite, usa la fecha/hora actual

**Caracteres de formato comunes:**

| Carácter | Descripción | Ejemplo |
|----------|-------------|---------|
| `Y` | Año con 4 dígitos | 2025 |
| `y` | Año con 2 dígitos | 25 |
| `m` | Mes con ceros (01-12) | 11 |
| `n` | Mes sin ceros (1-12) | 11 |
| `d` | Día con ceros (01-31) | 10 |
| `j` | Día sin ceros (1-31) | 10 |
| `H` | Hora 24h con ceros (00-23) | 14 |
| `i` | Minutos con ceros (00-59) | 30 |
| `s` | Segundos con ceros (00-59) | 45 |
| `l` | Día de la semana (texto completo) | Monday |
| `D` | Día de la semana (3 letras) | Mon |
| `F` | Mes (texto completo) | November |
| `M` | Mes (3 letras) | Nov |

**Ejemplos básicos:**
```php
echo date("Y-m-d");           // 2025-11-10
echo date("d/m/Y");           // 10/11/2025
echo date("Y-m-d H:i:s");     // 2025-11-10 14:30:45
echo date("l, j F Y");        // Monday, 10 November 2025
```

---

## `strtotime()` - Convertir texto a timestamp

La función `strtotime()` convierte cualquier descripción de fecha/hora en inglés a un timestamp Unix.

**Sintaxis:**
```php
strtotime(string $datetime, ?int $baseTimestamp = null): int|false
```

**Parámetros:**
- `$datetime`: String que describe una fecha/hora
- `$baseTimestamp`: Timestamp desde el cual calcular fechas relativas

**Retorna:**
- Timestamp Unix (int) si tiene éxito
- `false` si falla

---

## Suma de 4 meses - Ejemplo del código

### Código del archivo base_datos.php (línea 115):
```php
$fechaProximaDonacion = date("Y-m-d", strtotime($fechaDonacion . "+4 month"));
```

### Desglose paso a paso:

#### 1. **Concatenación de la fecha con la operación**
```php
$fechaDonacion . "+4 month"
// Si $fechaDonacion = "2025-11-10"
// Resultado: "2025-11-10 +4 month"
```

#### 2. **Conversión con `strtotime()`**
```php
strtotime("2025-11-10 +4 month")
```

**¿Qué hace?**
- Lee la fecha base: `2025-11-10`
- Aplica la operación: `+4 month` (suma 4 meses)
- Calcula la nueva fecha: 10 Marzo 2026
- Devuelve el timestamp Unix correspondiente

#### 3. **Formatear el resultado con `date()`**
```php
$fechaProximaDonacion = date("Y-m-d", strtotime("2025-11-10 +4 month"));
// Resultado: "2026-03-10"
```

---

## Sintaxis Especial para Sumar y Restar Tiempo

### 📝 Reglas de sintaxis

`strtotime()` acepta expresiones en inglés con esta estructura:

```
[+|-] <cantidad> <unidad>
```

### Unidades de tiempo disponibles:

| Unidad | Singular | Plural | Ejemplo |
|--------|----------|--------|---------|
| **Segundos** | `second` | `seconds` | `+30 seconds` |
| **Minutos** | `minute` | `minutes` | `-15 minutes` |
| **Horas** | `hour` | `hours` | `+2 hours` |
| **Días** | `day` | `days` | `+7 days` |
| **Semanas** | `week` | `weeks` | `-3 weeks` |
| **Meses** | `month` | `months` | `+4 months` |
| **Años** | `year` | `years` | `+1 year` |

⚠️ **Importante:** Tanto singular como plural son válidos (`+1 month` = `+1 months`)

---

## Ejemplos de Suma de Tiempo

### ➕ Sumar tiempo desde AHORA:
```php
// Sumar segundos
echo date("Y-m-d H:i:s", strtotime("+30 seconds"));  // Dentro de 30 segundos

// Sumar minutos
echo date("Y-m-d H:i:s", strtotime("+15 minutes"));  // Dentro de 15 minutos

// Sumar horas
echo date("Y-m-d H:i:s", strtotime("+2 hours"));     // Dentro de 2 horas

// Sumar días
echo date("Y-m-d", strtotime("+1 day"));             // Mañana
echo date("Y-m-d", strtotime("+7 days"));            // Dentro de 1 semana

// Sumar semanas
echo date("Y-m-d", strtotime("+1 week"));            // Próxima semana
echo date("Y-m-d", strtotime("+2 weeks"));           // Dentro de 2 semanas

// Sumar meses
echo date("Y-m-d", strtotime("+1 month"));           // Mes que viene
echo date("Y-m-d", strtotime("+4 months"));          // Dentro de 4 meses

// Sumar años
echo date("Y-m-d", strtotime("+1 year"));            // Próximo año
echo date("Y-m-d", strtotime("+5 years"));           // Dentro de 5 años
```

---

## Ejemplos de Resta de Tiempo

### ➖ Restar tiempo desde AHORA:
```php
// Restar días
echo date("Y-m-d", strtotime("-1 day"));             // Ayer
echo date("Y-m-d", strtotime("-7 days"));            // Hace 1 semana

// Restar semanas
echo date("Y-m-d", strtotime("-2 weeks"));           // Hace 2 semanas

// Restar meses
echo date("Y-m-d", strtotime("-1 month"));           // Mes pasado
echo date("Y-m-d", strtotime("-3 months"));          // Hace 3 meses

// Restar años
echo date("Y-m-d", strtotime("-1 year"));            // Año pasado
echo date("Y-m-d", strtotime("-10 years"));          // Hace 10 años

// Restar horas y minutos
echo date("H:i:s", strtotime("-2 hours"));           // Hace 2 horas
echo date("H:i:s", strtotime("-30 minutes"));        // Hace 30 minutos
```

---

## Combinaciones Múltiples

Puedes combinar **múltiples operaciones** en una sola expresión:

```php
// Sumar días y horas
echo date("Y-m-d H:i:s", strtotime("+2 days 3 hours"));
// Resultado: Dentro de 2 días y 3 horas

// Sumar semanas y días
echo date("Y-m-d", strtotime("+2 weeks 3 days"));
// Resultado: Dentro de 17 días (14 + 3)

// Sumar meses y días
echo date("Y-m-d", strtotime("+1 month 15 days"));
// Resultado: Dentro de 1 mes y 15 días

// Combinación mixta
echo date("Y-m-d H:i:s", strtotime("+1 year 2 months 5 days 3 hours"));
// Resultado: Fecha exacta calculada

// Restar combinado
echo date("Y-m-d", strtotime("-2 months 10 days"));
// Resultado: Hace 2 meses y 10 días
```

---

## Operaciones desde una Fecha Específica

### Con fecha base usando concatenación (como en el código):
```php
$fecha_base = "2025-11-10";

// Sumar 4 meses desde fecha específica
$nueva_fecha = date("Y-m-d", strtotime($fecha_base . " +4 months"));
echo $nueva_fecha; // 2026-03-10

// Restar 2 semanas desde fecha específica
$fecha_pasada = date("Y-m-d", strtotime($fecha_base . " -2 weeks"));
echo $fecha_pasada; // 2025-10-27

// Combinación desde fecha específica
$fecha_compleja = date("Y-m-d", strtotime($fecha_base . " +1 year 6 months"));
echo $fecha_compleja; // 2027-05-10
```

### Con fecha base usando segundo parámetro:
```php
$fecha_base = "2025-11-10";
$timestamp_base = strtotime($fecha_base);

// Sumar 4 meses
$nueva_fecha = date("Y-m-d", strtotime("+4 months", $timestamp_base));
echo $nueva_fecha; // 2026-03-10

// Restar 1 año
$fecha_anterior = date("Y-m-d", strtotime("-1 year", $timestamp_base));
echo $fecha_anterior; // 2024-11-10
```

---

## Expresiones Descriptivas en Inglés

`strtotime()` también entiende expresiones naturales:

```php
// Próximos días específicos
echo date("Y-m-d", strtotime("next Monday"));        // Próximo lunes
echo date("Y-m-d", strtotime("next Friday"));        // Próximo viernes
echo date("Y-m-d", strtotime("next week"));          // Próxima semana

// Días pasados
echo date("Y-m-d", strtotime("last Monday"));        // Último lunes
echo date("Y-m-d", strtotime("last Friday"));        // Último viernes
echo date("Y-m-d", strtotime("last week"));          // Semana pasada

// Referencias temporales
echo date("Y-m-d", strtotime("yesterday"));          // Ayer
echo date("Y-m-d", strtotime("today"));              // Hoy
echo date("Y-m-d", strtotime("tomorrow"));           // Mañana
echo date("Y-m-d", strtotime("now"));                // Ahora

// Inicio y fin de periodos
echo date("Y-m-d", strtotime("first day of this month"));      // 2025-11-01
echo date("Y-m-d", strtotime("last day of this month"));       // 2025-11-30
echo date("Y-m-d", strtotime("first day of next month"));      // 2025-12-01
echo date("Y-m-d", strtotime("last day of next month"));       // 2025-12-31
echo date("Y-m-d", strtotime("first day of January 2026"));    // 2026-01-01

// Combinaciones
echo date("Y-m-d", strtotime("first day of +3 months"));       // Primer día dentro de 3 meses
echo date("Y-m-d", strtotime("last day of -2 months"));        // Último día hace 2 meses
```

---

## Tabla Resumen de Operaciones

| Operación | Sintaxis | Ejemplo | Resultado (desde 2025-11-10) |
|-----------|----------|---------|------------------------------|
| Sumar días | `+N days` | `+7 days` | 2025-11-17 |
| Restar días | `-N days` | `-7 days` | 2025-11-03 |
| Sumar semanas | `+N weeks` | `+2 weeks` | 2025-11-24 |
| Restar semanas | `-N weeks` | `-2 weeks` | 2025-10-27 |
| Sumar meses | `+N months` | `+4 months` | 2026-03-10 |
| Restar meses | `-N months` | `-4 months` | 2025-07-10 |
| Sumar años | `+N years` | `+1 year` | 2026-11-10 |
| Restar años | `-N years` | `-1 year` | 2024-11-10 |
| Combinado | `+N unit M unit` | `+1 month 15 days` | 2025-12-25 |

---

## Casos Especiales con Meses

### ⚠️ Advertencia: Final de mes

Cuando sumas/restas meses desde fechas que no existen en el mes destino:

```php
// Si la fecha es 31 Enero 2025
$fecha = "2025-01-31";

// Sumar 1 mes
echo date("Y-m-d", strtotime($fecha . " +1 month"));
// Resultado: 2025-03-03 (no 2025-02-31, porque Febrero no tiene 31 días)
// PHP "salta" los días que faltan

// Sumar 2 meses
echo date("Y-m-d", strtotime($fecha . " +2 months"));
// Resultado: 2025-03-31 (Marzo sí tiene 31 días)
```

### ✅ Solución: Usar primer/último día del mes

```php
$fecha = "2025-01-31";

// Obtener primer día del mes resultante
echo date("Y-m-d", strtotime("first day of " . $fecha . " +1 month"));
// Resultado: 2025-02-01

// Obtener último día del mes resultante
echo date("Y-m-d", strtotime("last day of " . $fecha . " +1 month"));
// Resultado: 2025-02-28 (o 29 en año bisiesto)
```

---

## Ejemplo Completo del Contexto (Donaciones)

```php
<?php
// Contexto: Sistema de donaciones de sangre
// Regla: Próxima donación permitida 4 meses después

function dar_alta_donacion($conexion, $idDonante, $fechaDonacion)
{
    // Ejemplo: $fechaDonacion = "2025-11-10"
    
    // Calcular fecha próxima donación (4 meses después)
    $fechaProximaDonacion = date("Y-m-d", strtotime($fechaDonacion . " +4 months"));
    // Resultado: "2026-03-10"
    
    // Insertar en base de datos
    $consulta = $conexion->prepare("INSERT INTO historico (idDonante, fechaDonacion, proximaDonacion) 
                                     VALUES (:idDonante, :fechaDonacion, :proximaDonacion)");
    $consulta->bindParam(":idDonante", $idDonante);
    $consulta->bindParam(":fechaDonacion", $fechaDonacion);
    $consulta->bindParam(":proximaDonacion", $fechaProximaDonacion);
    $consulta->execute();
}

// Uso
$fechaDonacion = "2025-11-10";
$proximaDonacion = date("Y-m-d", strtotime($fechaDonacion . " +4 months"));
echo "Donación: $fechaDonacion\n";        // 2025-11-10
echo "Próxima: $proximaDonacion\n";       // 2026-03-10

// Verificar si puede donar hoy
$hoy = date("Y-m-d");
if ($hoy >= $proximaDonacion) {
    echo "Puede donar nuevamente\n";
} else {
    echo "Debe esperar hasta: $proximaDonacion\n";
}
?>
```

---

## Ejemplo Práctico: Calculadora de Fechas

```php
<?php
// Calculadora de plazos

$fecha_inicio = "2025-11-10";

echo "Fecha inicio: $fecha_inicio\n\n";

// Diferentes plazos
$plazos = [
    "1 semana" => "+1 week",
    "15 días" => "+15 days",
    "1 mes" => "+1 month",
    "3 meses" => "+3 months",
    "6 meses" => "+6 months",
    "1 año" => "+1 year",
    "2 años 6 meses" => "+2 years 6 months"
];

foreach ($plazos as $descripcion => $operacion) {
    $fecha_resultado = date("Y-m-d", strtotime($fecha_inicio . " " . $operacion));
    echo "$descripcion: $fecha_resultado\n";
}

/* Salida:
Fecha inicio: 2025-11-10

1 semana: 2025-11-17
15 días: 2025-11-25
1 mes: 2025-12-10
3 meses: 2026-02-10
6 meses: 2026-05-10
1 año: 2026-11-10
2 años 6 meses: 2028-05-10
*/
?>
```

---

## Comparación de Fechas

```php
<?php
// Comparar fechas usando timestamps

$fecha1 = "2025-11-10";
$fecha2 = "2026-03-10";

$timestamp1 = strtotime($fecha1);
$timestamp2 = strtotime($fecha2);

if ($timestamp1 < $timestamp2) {
    echo "$fecha1 es anterior a $fecha2\n";
}

// Calcular diferencia en días
$diferencia_segundos = $timestamp2 - $timestamp1;
$diferencia_dias = $diferencia_segundos / (60 * 60 * 24);
echo "Diferencia: " . round($diferencia_dias) . " días\n";
// Resultado: Diferencia: 120 días
?>
```

---

## Resumen

1. **`date()`**: Formatea timestamps en strings legibles
2. **`strtotime()`**: Convierte strings de fecha/hora en timestamps Unix
3. **Sintaxis de suma/resta**: `+/-N unit` (singular o plural)
4. **Unidades válidas**: `second(s)`, `minute(s)`, `hour(s)`, `day(s)`, `week(s)`, `month(s)`, `year(s)`
5. **Combinaciones**: Puedes sumar múltiples unidades: `+1 year 6 months 15 days`
6. **Desde fecha específica**: Concatenar fecha + operación: `$fecha . " +4 months"`
7. **Expresiones naturales**: `next Monday`, `last week`, `first day of next month`
8. **Cuidado con meses**: Fechas como 31 pueden "saltar" días en meses más cortos

**Ventajas**: 
- Maneja automáticamente años bisiestos
- Ajusta diferentes longitudes de meses
- Calcula cambios de año automáticamente
- Sintaxis flexible y legible
