<?php
// Clase final: no se puede heredar
final class Fruit {
    public $name;

    public function __construct($name) {
        $this->name = $name;
    }

    // Método final: no se puede sobrescribir
    final public function intro() {
        echo "Soy un/una {$this->name}<br>";
    }
}

// Esto daría error (descomentar para probar):
// class Strawberry extends Fruit {}  // ERROR: Fruit es final

// Esto funciona:
$apple = new Fruit("Manzana");
$apple->intro();

// Ejemplo de método final en clase no final
class Car {
    public $brand;

    final public function start() {
        echo "Coche arrancado<br>";
    }
}

/** @disregard P1037 */
class ElectricCar extends Car {
    // Esto daría error (descomentar para probar):
    // public function start() {}  // ERROR: start() es final
}

$tesla = new ElectricCar("Tesla");
$tesla->start();  // OK: usa método heredado
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>