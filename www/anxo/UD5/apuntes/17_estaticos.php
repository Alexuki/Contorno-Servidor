<?php
class Saludar {
    // Método estático
    public static function saludo() {
        echo "Hola Mundo!<br>";
    }

    // Llamar estático desde constructor con self::
    public function __construct() {
        self::saludo();
    }
}

echo "<h2>Llamar método estático sin crear objeto</h2>";
// NombreClase::metodoEstatico()
Saludar::saludo();

echo "<h2>Llamar desde constructor (self::)</h2>";
new Saludar();

// Ejemplo: contador de instancias
class Usuario {
    public static $contador = 0;  // Propiedad estática
    public $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
        self::$contador++;  // Acceder con self::
    }

    public static function getTotalUsuarios() {
        return "Total usuarios creados: " . self::$contador . "<br>";
    }
}

echo "<h2>Propiedades estáticas (contador)</h2>";
echo Usuario::getTotalUsuarios();  // 0

$u1 = new Usuario("Ana");
$u2 = new Usuario("Luis");
$u3 = new Usuario("Pedro");

echo Usuario::getTotalUsuarios();  // 3
echo "Acceso directo: " . Usuario::$contador . "<br>";

// Llamar desde otra clase
class Persona {
    public function mensaje() {
        Saludar::saludo();  // Llamar estático de otra clase
    }
}

echo "<h2>Llamar estático desde otra clase</h2>";
$persona = new Persona();
$persona->mensaje();
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>