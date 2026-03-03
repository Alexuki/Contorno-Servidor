<?php
class Goodbye {
    // Declarar constante con const
    const LEAVING_MESSAGE = "Adiós, nos vemos pronto!";

    // Acceder desde dentro de la clase con self::
    public function byebye() {
        echo self::LEAVING_MESSAGE . "<br>";
    }
}

echo "<h2>Acceder desde fuera de la clase</h2>";
// Operador de resolución de ámbito ::
echo Goodbye::LEAVING_MESSAGE . "<br>";

echo "<h2>Acceder desde dentro de la clase</h2>";
$goodbye = new Goodbye();
$goodbye->byebye();

// Ejemplo práctico: configuración de aplicación
class Configuracion {
    const VERSION = "1.5.2";
    const NOMBRE_APP = "Mi Aplicación";
    const MAX_USUARIOS = 100;
    const DB_HOST = "localhost";

    public function mostrarInfo() {
        echo "App: " . self::NOMBRE_APP . "<br>";
        echo "Versión: " . self::VERSION . "<br>";
    }
}

echo "<h2>Configuración de aplicación</h2>";
echo "Versión: " . Configuracion::VERSION . "<br>";
echo "Máximo usuarios: " . Configuracion::MAX_USUARIOS . "<br>";

$config = new Configuracion();
$config->mostrarInfo();

// Las constantes no se pueden modificar
// Configuracion::VERSION = "2.0"; // ERROR
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>