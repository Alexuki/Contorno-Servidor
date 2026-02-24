<?php
class Fruit {
    public $name;        // Accesible desde cualquier lugar
    protected $color;    // Accesible solo dentro de la clase y herencias
    private $weight;     // Accesible solo dentro de esta clase

    // Métodos públicos para acceder a propiedades protegidas/privadas
    function setColor($c) {
        $this->color = $c;  // OK: estamos dentro de la clase
    }

    function getColor() {
        return $this->color;
    }

    // Se pueden introducir restricciones dentro del setter
    function setWeight($w) {
        if ($w>= 0) {
            $this->weight = $w;
        }
        else {
            $this->weight = 0;
        }
    }

    function getWeight() {
        return $this->weight;
    }
}

$mango = new Fruit();

// Public: funciona
$mango->name = 'Mango';
echo "Nombre: " . $mango->name . "<br>";

// Protected y private: usar métodos públicos
$mango->setColor('Amarillo');
$mango->setWeight(-50);

echo "Color: " . $mango->getColor() . "<br>";
echo "Peso: " . $mango->getWeight() . "g<br>";

// Esto daría error (descomentar para probar):
// $mango->color = 'Rojo';    // ERROR: protected
// $mango->weight = 500;      // ERROR: private
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>