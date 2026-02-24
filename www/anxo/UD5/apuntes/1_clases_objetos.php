<?php
// Definir clase Fruit
class Fruit {
    // Propiedades (atributos)
    public $name;
    public $color;

    // Constructor vacío explícito
    function __construct() {
    }

    // Métodos (funciones)
    function setName($n) {
        $this->name = $n;
    }

    function getName() {
        return $this->name;
    }

    function setColor($c) {
        $this->color = $c;
    }

    function getColor() {
        return $this->color;
    }
}

// Crear objetos (instancias)
$apple = new Fruit();
$banana = new Fruit();

// Usar métodos para establecer valores
$apple->setName('Manzana');
$apple->setColor('Roja');

$banana->setName('Plátano');
$banana->setColor('Amarillo');

// Mostrar valores
echo "Fruta 1: " . $apple->getName() . " - " . $apple->getColor() . "<br>";
echo "Fruta 2: " . $banana->getName() . " - " . $banana->getColor() . "<br>";

// Acceder directamente a propiedades públicas
$apple->name = 'Apple';
echo "Nombre directo: " . $apple->name . "<br>";
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>