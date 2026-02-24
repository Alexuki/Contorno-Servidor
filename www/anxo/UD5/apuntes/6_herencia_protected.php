<?php
class Fruit {
    public $name;
    public $color;

    public function __construct($name, $color) {
        $this->name = $name;
        $this->color = $color;
    }

    // Método protegido: accesible en clase y herencias
    protected function intro() {
        echo "Soy {$this->name} de color {$this->color}<br>";
    }
}

class Strawberry extends Fruit {
    public function message() {
        echo "¿Soy fruta o baya? ";
        // OK: intro() es protected, accesible desde clase hija
        $this->intro();
    }
}

/** @disregard P1005 */
$strawberry = new Strawberry("Fresa", "Roja");
$strawberry->message();  // OK: método público

// Esto daría error (descomentar para probar):
// $strawberry->intro();  // ERROR: intro() es protected
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>