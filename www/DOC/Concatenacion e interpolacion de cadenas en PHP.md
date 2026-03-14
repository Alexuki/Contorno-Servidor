# Concatenacion e interpolacion de cadenas en PHP

Este documento unifica los conceptos de concatenacion e interpolacion de cadenas en PHP, incluyendo casos con `if`, objetos y llamadas a metodos.

## Regla rapida

En cadenas con comillas dobles (`"..."`), PHP permite:
- Variables: `$nombre`
- Acceso a propiedades: `$this->nombre` o `{$contacto->nombre}`

Pero no permite directamente:
- Llamadas a metodos dentro de la interpolacion: `$contacto->getNombre()`

## Ejemplo del error mas comun

Esto da error:

```php
echo "Contacto 1: $contacto1->getNombre() ";
```

Hazlo asi (concatenacion):

```php
echo "Contacto 1: " . $contacto1->getNombre() . " ";
```

Tambien puedes resolver el metodo antes:

```php
$nombre = $contacto1->getNombre();
echo "Contacto 1: $nombre ";
```

## Concatenar string con otras en funcion de un if y usando varias lineas

### Opcion 1: Concatenar con echo dentro del if

```php
echo "test if($b): ";
if($b){
    echo "entra en if";
}
echo "<br>";
```

### Opcion 2: Usar operador ternario (mas compacto)

```php
echo "test if($b): " . ($b ? "entra en if" : "") . "<br>";
```

### Opcion 3: Concatenar en una variable

```php
$resultado = "test if($b): ";
if($b){
    $resultado .= "entra en if";
}
$resultado .= "<br>";
echo $resultado;
```

### Opcion 4: Usar sintaxis alternativa de control (mas legible en HTML)

```php
echo "test if($b): ";
if($b):
    echo "entra en if";
endif;
echo "<br>";
```

## Notas importantes

- No puedes usar `.` solo (operador de concatenacion) sin `echo` o asignacion.
- El `.` debe estar en una expresion completa.
- Para objetos, usa concatenacion cuando necesites ejecutar metodos.
- Si interpolas propiedades complejas, usa llaves para mayor claridad: `"{$obj->propiedad}"`.
