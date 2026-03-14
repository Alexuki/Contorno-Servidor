# Fechas en PHP: `date()` y formateo

## Funcion `date()`
`date()` devuelve una fecha/hora en formato texto segun el patron que le pases.

Sintaxis:

```php
string date(string $format, ?int $timestamp = null)
```

- `$format`: mascara de salida.
- `$timestamp`: opcional. Si no se indica, usa la fecha/hora actual del servidor.

Ejemplo basico:

```php
echo date("d/m/Y"); // 09/03/2026
```

## Formatos mas usados

```php
Y  // anio con 4 digitos (2026)
y  // anio con 2 digitos (26)
m  // mes con cero inicial (01-12)
n  // mes sin cero inicial (1-12)
F  // nombre del mes en ingles (March)
d  // dia con cero inicial (01-31)
j  // dia sin cero inicial (1-31)
H  // hora en formato 24h (00-23)
i  // minutos (00-59)
s  // segundos (00-59)
w  // dia de la semana numerico (0=Domingo, 6=Sabado)
l  // nombre del dia en ingles (Monday)
```

Ejemplos:

```php
echo date("Y-m-d");      // 2026-03-09
echo date("H:i:s");      // 14:35:07
echo date("d/m/Y H:i:s"); // 09/03/2026 14:35:07
```

## Fecha en texto en espanol
`date()` no traduce automaticamente a espanol. Una forma habitual en clase es usar arrays para dias y meses:

```php
$dias = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];
$meses = [1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

$texto = $dias[date("w")] . " " . date("j") . " de " . $meses[date("n")] . " del " . date("Y");
echo $texto;
```

Salida posible:

```text
Lunes 9 de Marzo del 2026
```

## Configurar zona horaria
Para evitar fechas/horas incorrectas, define la zona horaria:

```php
date_default_timezone_set("Europe/Madrid");
```

Si no lo haces, en muchos entornos (Docker, servidores Linux, etc.) PHP puede usar `UTC` por defecto y mostrar una hora distinta a la local.

Ejemplo practico para este caso:

```php
date_default_timezone_set("Europe/Madrid");
echo date("H:i:s"); // Hora local de Madrid
```

Puedes revisarla con:

```php
echo date_default_timezone_get();
```

## Buenas practicas
- Mostrar en pantalla con `date()` cuando solo necesitas texto rapido.
- Para operaciones complejas (sumar/restar periodos), usar `DateTime` y `DateInterval`.
- Establecer siempre zona horaria al inicio de la aplicacion.
