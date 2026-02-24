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

// Con herencia, los atributos privados de la clase padre no son accesibles
// desde la clase hija.

// Clase hija (hereda de Fruit)
class Strawberry extends Fruit {
    // Método propio de la clase hija
    public function message() {
        echo "¿Soy fruta o baya? ";
    }
}

/**
 * Comentario @disregard P1005
 * Disables PHP CodeSniffer warning P1005 for the following code.
 * P1005 is typically a "Class declaration should be preceded by a blank line" warning.
 * This directive tells the code analyzer to ignore this specific rule for the code below.
 */
// Crear objeto de clase hija
/** @disregard P1005 */
$strawberry = new Strawberry("Fresa", "Roja");

// Usar método propio
$strawberry->message();

// Usar método heredado
$strawberry->intro();
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>