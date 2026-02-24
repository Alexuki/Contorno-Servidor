<?php
// Clase padre
class Fruit {
    public $name;
    public $color;

    public function __construct($name, $color) {
        $this->name = $name;
        $this->color = $color;
    }

    public function intro() {
        echo "Soy una {$this->name} de color {$this->color}<br>";
    }
}

// Clase hija (hereda de Fruit)
class Strawberry extends Fruit {
    // Método propio de la clase hija
    public function message() {
        echo "¿Soy fruta o baya? ";
    }
}

// Crear objeto de clase hija
/** @disregard P1005 */
$strawberry = new Strawberry("Fresa", "Roja");

// Usar método propio
$strawberry->message();

// Usar método heredado
$strawberry->intro();
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>