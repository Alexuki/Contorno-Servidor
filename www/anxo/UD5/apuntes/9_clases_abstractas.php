<?php
// Clase abstracta: no se puede instanciar directamente
abstract class Car {
    public $modelo;

    public function __construct($name) {
        $this->modelo = $name;
    }

    // Método abstracto: debe implementarse en clases hijas
    abstract public function intro();
}

// Clases hijas que implementan el método abstracto
class Audi extends Car {
    public function intro(){
        return "Calidad alemana. Soy un Audi $this->modelo!";
    }
}

class Volvo extends Car {
    public function intro(){
        return "Orgullo sueco. Soy un Volvo $this->modelo!";
    }
}

// Crear objetos de clases hijas
$audi = new Audi("A3");
echo $audi->intro() . "<br>";

$volvo = new Volvo("EX30");
echo $volvo->intro() . "<br>";

// Esto daría error (descomentar para probar):
// $car = new Car("Genérico");  // ERROR: no se puede instanciar clase abstracta
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>