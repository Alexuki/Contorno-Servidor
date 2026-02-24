<?php
class Fruit {
    public $name;
    public $color;

    public function __construct($name, $color) {
        $this->name = $name;
        $this->color = $color;
    }

    public function intro() {
        echo "Soy una fruta: {$this->name}, de color {$this->color}<br>";
    }
}

class Strawberry extends Fruit {
    public $weight;  // Propiedad nueva

    // Sobrescribir constructor
    public function __construct($weight) {
        $this->name = "Fresa";
        $this->color = "Rojo";
        $this->weight = $weight;
    }

    // Sobrescribir método
    public function intro() {
        echo "Soy un/una {$this->name}, de color {$this->color} y peso {$this->weight}g<br>";
    }
}

// Usar clase padre
$fruit = new Fruit("Fruta genérica", "Verde");
$fruit->intro();

// Usar clase hija con métodos sobrescritos
$strawberry = new Strawberry(50);
$strawberry->intro();
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>