<?php
// TRAIT: reutilizar código en múltiples clases
/*
Similares a interfaces pero la interfaz es una plantilla a seguir
obligatoriamente. Obliga a implementar todos los métodos.
Un trait es como una librería con distintos métodos para cargar.
Es para reutilizar métodos en diferentes clases que no heredan o
no usan interfaces. Se crea un trait con métodos ya implementados
y se cargan en las clases mediante use. Eso es como copiar y pegar
el código dentro de la clase.
Además, solo se cargan una vez, no se cargan cada vez que ponemos use.
El trait se suele poner al inicio de la clase pero puede ponerse después.
Los traits pueden usarse dentro de una clase pero también desde fuera
mediante una instancia de una clase que usa el trait.
*/

trait Mensaje {
    public function mostrarMensaje($texto) {
        echo "Mensaje: $texto<br>";
    }
}

trait Fecha {
    public function mostrarFecha() {
        echo "Fecha actual: " . date("d/m/Y") . "<br>";
    }
}

trait Calculadora {
    public function sumar($a, $b) {
        return $a + $b;
    }

    public function restar($a, $b) {
        return $a - $b;
    }
}

// Clase que usa un trait
class Usuario {
    use Mensaje;

    public $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function saludar() {
        $this->mostrarMensaje("Hola, soy $this->nombre");
    }
}

// Clase que usa múltiples traits
class Producto {
    use Mensaje, Fecha, Calculadora;

    public $nombre;
    public $precio;

    public function __construct($nombre, $precio) {
        $this->nombre = $nombre;
        $this->precio = $precio;
    }

    public function info() {
        $this->mostrarMensaje("Producto: $this->nombre");
        $this->mostrarFecha();
        echo "Precio con IVA: " . $this->sumar($this->precio, $this->precio * 0.21) . "€<br>";
    }
}

echo "<h2>Clase Usuario (1 trait)</h2>";
$user = new Usuario("Ana");
$user->saludar();

echo "<h2>Clase Producto (3 traits)</h2>";
$product = new Producto("Laptop", 800);
$product->info();

echo "<h2>Uso directo de métodos del trait</h2>";
$product->mostrarMensaje("Texto directo");
echo "Suma: " . $product->sumar(10, 5) . "<br>";
echo "Resta: " . $product->restar(10, 5) . "<br>";
?>
<p style="margin-top: 2rem;"><a href="menu.php">← Volver al menú</a></p>