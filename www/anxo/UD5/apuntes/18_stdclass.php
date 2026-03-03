<?php
// Crear objeto vacío con stdClass
$obj = new stdClass();

// Añadir propiedades dinámicamente
$obj->nombre = "Raquel";
$obj->apellido = "Platero";
$obj->edad = 25;
$obj->direccion = "Santiago de Compostela, A Coruña";

echo "<h2>Objeto stdClass con propiedades dinámicas</h2>";
echo "Nombre: " . $obj->nombre . "<br>";
echo "Apellido: " . $obj->apellido . "<br>";
echo "Edad: " . $obj->edad . "<br>";
echo "Dirección: " . $obj->direccion . "<br>";

// Convertir a JSON (uso típico)
echo "<h2>Convertir a JSON para enviar a otro sistema</h2>";
$json = json_encode($obj);
echo $json . "<br>";

// Crear desde array
$datos = [
    "producto" => "Laptop",
    "precio" => 899.99,
    "stock" => 15
];

// Convertir array a objeto
$producto = (object)$datos;

echo "<h2>Array convertido a objeto</h2>";
echo "Producto: " . $producto->producto . "<br>";
echo "Precio: " . $producto->precio . "€<br>";

// Crear objeto vacío para respuesta API
$respuesta = new stdClass();
$respuesta->exito = true;
$respuesta->mensaje = "Operación completada";
$respuesta->datos = $obj;

echo "<h2>Objeto anidado para API</h2>";
echo "<pre>";
print_r($respuesta);
echo "</pre>";
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>