<?php
class Fruit {
    public $name;
    public $color;

    // Constructor: se ejecuta automáticamente al crear el objeto
    function __construct($name, $color) {
        $this->name = $name;
        $this->color = $color;
        echo "Creada: $name de color $color<br>";
    }

    // Destructor: se ejecuta automáticamente al destruir el objeto (útil para cerrar conexiones, liberar memoria..)
    function __destruct() {
        echo "Destruida: {$this->name}<br>";
    }

    function get_details() {
         echo "Nombre: " . $this->name . ". Color: " . $this->color .".<br>";
    }
}

// Crear objeto (el constructor se llama automáticamente)
$apple = new Fruit("Manzana", "Roja");
$apple->get_details();

// Al final del script, el destructor se llama automáticamente
echo "Fin del script<br>";
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>