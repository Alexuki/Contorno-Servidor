<?php
// Una clase puede implementar MÚLTIPLES interfaces

interface Volador {
    public function volar();
}

interface Nadador {
    public function nadar();
}

interface Cantante {
    public function cantar();
}

// Pato: puede volar, nadar y cantar
class Pato implements Volador, Nadador, Cantante {
    public function volar() {
        echo "El pato vuela<br>";
    }

    public function nadar() {
        echo "El pato nada<br>";
    }

    public function cantar() {
        echo "El pato hace cuac cuac<br>";
    }
}

// Pez: solo nada
class Pez implements Nadador {
    public function nadar() {
        echo "El pez nada bajo el agua<br>";
    }
}

// Canario: vuela y canta
class Canario implements Volador, Cantante {
    public function volar() {
        echo "El canario vuela alto<br>";
    }

    public function cantar() {
        echo "El canario canta bien<br>";
    }
}

echo "<h2>Pato (3 interfaces)</h2>";
$pato = new Pato();
$pato->volar();
$pato->nadar();
$pato->cantar();

echo "<h2>Pez (1 interfaz)</h2>";
$pez = new Pez();
$pez->nadar();

echo "<h2>Canario (2 interfaces)</h2>";
$canario = new Canario();
$canario->volar();
$canario->cantar();

echo "<h2>Verificación con instanceof</h2>";
echo "¿Pato es Volador? " . ($pato instanceof Volador ? "Sí" : "No") . "<br>";
echo "¿Pato es Nadador? " . ($pato instanceof Nadador ? "Sí" : "No") . "<br>";
echo "¿Pez es Volador? " . ($pez instanceof Volador ? "Sí" : "No") . "<br>";
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>