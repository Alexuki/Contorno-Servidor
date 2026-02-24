<?php
/**
 * Clase para operaciones matemáticas básicas.
 *
 * @author Alumno <alumno@dominio.com>
 * @copyright 2025
 * @license GPL
 */
class Math {

    /**
     * Suma dos números.
     *
     * @param int $a Primer número
     * @param int $b Segundo número
     * @return int Resultado de la suma
     */
    public function add($a, $b) {
        return $a + $b;
    }

    /**
     * Resta dos números.
     *
     * @param int $a Primer número
     * @param int $b Segundo número
     * @return int Resultado de la resta
     */
    public function subtract($a, $b) {
        return $a - $b;
    }

    /**
     * Multiplica dos números.
     *
     * @param int $a Primer número
     * @param int $b Segundo número
     * @return int Resultado de la multiplicación
     */
    public function multiply($a, $b) {
        return $a * $b;
    }
}

// Uso de la clase documentada
$math = new Math();
echo "Suma: " . $math->add(5, 3) . "<br>";
echo "Resta: " . $math->subtract(10, 4) . "<br>";
echo "Multiplicación: " . $math->multiply(6, 7) . "<br>";
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>