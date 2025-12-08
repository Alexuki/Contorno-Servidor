# Concatenar string con otras en función de un if y usando varias líneas

## **Opción 1: Concatenar con echo dentro del if**
```php
echo "test if($b): ";
if($b){
    echo "entra en if";
}
echo "<br>";
```

## **Opción 2: Usar operador ternario (más compacto)**
```php
echo "test if($b): " . ($b ? "entra en if" : "") . "<br>";
```

## **Opción 3: Concatenar en una variable**
```php
$resultado = "test if($b): ";
if($b){
    $resultado .= "entra en if";
}
$resultado .= "<br>";
echo $resultado;
```

## **Opción 4: Usar sintaxis alternativa de control (más legible en HTML)**
```php
echo "test if($b): ";
if($b): 
    echo "entra en if";
endif;
echo "<br>";
```

---

- No puedes usar `.` solo (operador de concatenación) sin `echo` o asignación
- El `.` debe estar en una expresión completa
