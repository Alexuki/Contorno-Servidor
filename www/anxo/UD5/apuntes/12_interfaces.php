<?php
// Interfaz: define qué métodos deben implementar las clases
interface Animal {
    public function makeSound();
    public function move();
}

// Clase que implementa la interfaz
class Cat implements Animal {
    public function makeSound() {
        echo "Meow<br>";
    }

    public function move() {
        echo "El gato camina silenciosamente<br>";
    }
}

// Otra clase que implementa la misma interfaz
class Dog implements Animal {
    public function makeSound() {
        echo "Woof<br>";
    }

    public function move() {
        echo "El perro corre felizmente<br>";
    }
}

// Tercera clase que implementa la interfaz
class Bird implements Animal {
    public function makeSound() {
        echo "Tweet<br>";
    }

    public function move() {
        echo "El pájaro vuela por el cielo<br>";
    }
}

// Crear objetos y usar los métodos
echo "<h2>Gato</h2>";
$cat = new Cat();
$cat->makeSound();
$cat->move();

echo "<h2>Perro</h2>";
$dog = new Dog();
$dog->makeSound();
$dog->move();

echo "<h2>Pájaro</h2>";
$bird = new Bird();
$bird->makeSound();
$bird->move();

// Verificar que todos son Animales
// instanceof funciona para clases y para interfaces
echo "<h2>Verificación con instanceof</h2>";
$animals = [$cat, $dog, $bird];
foreach ($animals as $animal) {
    if ($animal instanceof Animal) {
        echo "Es un Animal<br>";
    }
}
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>