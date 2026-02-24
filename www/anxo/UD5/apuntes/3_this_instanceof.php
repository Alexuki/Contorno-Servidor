<?php
class Fruit {
    public $name;
    public $color;

        // Otra forma de crear los getters y setters
    function set_details($name, $color) {
        // $this se refiere al objeto actual
        $this->name = $name;
        $this->color = $color;
    }

    function get_details() {
        echo "Nombre: " . $this->name . ". Color: " . $this->color .".<br>";
    }
}

$apple = new Fruit();
$apple->set_details("Manzana","Rojo");

// instanceof: verificar si es instancia de una clase
if ($apple instanceof Fruit) {
    echo "$apple->name es una instancia de la clase Fruit<br>";
}


$banana = new Fruit();
$banana->set_details("Plátano","Amarillo");

$apple->get_details();
$banana->get_details();
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>