<?php
// INTERFAZ: solo define métodos, sin implementación
interface FormaInterface {
    public function calcularArea();
    public function calcularPerimetro();
}

// CLASE ABSTRACTA: puede tener métodos concretos y abstractos
abstract class FormaAbstracta {
    protected $color;

    // Método concreto (con implementación)
    public function setColor($color) {
        $this->color = $color;
    }

    public function getColor() {
        return $this->color;
    }

    // Método abstracto (sin implementación)
    abstract public function calcularArea();
}

// Rectángulo implementa la INTERFAZ
class RectanguloInterface implements FormaInterface {
    private $base;
    private $altura;

    public function __construct($base, $altura) {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function calcularArea() {
        return $this->base * $this->altura;
    }

    public function calcularPerimetro() {
        return 2 * ($this->base + $this->altura);
    }
}

// Rectángulo extiende la CLASE ABSTRACTA
class RectanguloAbstracto extends FormaAbstracta {
    private $base;
    private $altura;

    public function __construct($base, $altura) {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function calcularArea() {
        return $this->base * $this->altura;
    }
}

echo "<h2>Usando INTERFAZ</h2>";
$rect1 = new RectanguloInterface(5, 3);
echo "Área: " . $rect1->calcularArea() . "<br>";
echo "Perímetro: " . $rect1->calcularPerimetro() . "<br>";

echo "<h2>Usando CLASE ABSTRACTA</h2>";
$rect2 = new RectanguloAbstracto(5, 3);
$rect2->setColor("Rojo");
echo "Área: " . $rect2->calcularArea() . "<br>";
echo "Color: " . $rect2->getColor() . "<br>";

echo "<h2>Diferencias clave</h2>";
echo "<ul>";
echo "<li>Interfaz: solo métodos públicos, sin implementación</li>";
echo "<li>Clase abstracta: puede tener métodos concretos y diferentes visibilidades</li>";
echo "<li>Una clase puede implementar VARIAS interfaces</li>";
echo "<li>Una clase solo puede extender UNA clase abstracta</li>";
echo "</ul>";
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>