<?php
// Clase anónima: sin nombre, usa única
$greeting = new class {
    public function sayHello() {
        return "Hola desde clase anónima!";
    }
};

/*
Pueden ser útiles para crear una función dentro de la estructura de POO,
creando un objeto con un método, sin crear una función suelta.
*/

echo $greeting->sayHello() . "<br>";

// Con constructor
$math = new class(10, 5) {
    private $a;
    private $b;

    public function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
    }

    public function sum() {
        return $this->a + $this->b;
    }

    public function multiply() {
        return $this->a * $this->b;
    }
};

echo "Suma: " . $math->sum() . "<br>";
echo "Multiplicación: " . $math->multiply() . "<br>";

// Uso típico: callback o implementación rápida
$processor = new class {
    public function process($data) {
        return strtoupper($data);
    }
};

echo $processor->process("hola mundo") . "<br>";
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>