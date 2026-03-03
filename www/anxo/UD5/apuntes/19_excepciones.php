<?php
function inverso($x) {
    // Si $x es 0 (false), lanzar excepción
    if (!$x) {
        throw new Exception('División por cero.');
    }
    return 1 / $x;
}

echo "<h2>Captura de excepciones con try-catch</h2>";

try {
    echo "Inverso de 5: " . inverso(5) . "<br>";
    echo "Inverso de 0: " . inverso(0) . "<br>";  // Esto lanzará excepción
    echo "Esto no se ejecuta<br>";  // Se salta por el error
} catch (Exception $e) {
    echo "<span style='color:red'>Excepción capturada: " . $e->getMessage() . "</span><br>";
}

// El programa continúa
echo "<p>El programa sigue ejecutándose...</p>";

// Ejemplo: validar edad
function validarEdad($edad) {
    if ($edad < 0) {
        throw new Exception("La edad no puede ser negativa");
    }
    if ($edad > 150) {
        throw new Exception("La edad no es realista");
    }
    return "Edad válida: $edad";
}

echo "<h2>Validación con excepciones</h2>";

$edades = [25, -5, 200, 30];

foreach ($edades as $edad) {
    try {
        echo validarEdad($edad) . "<br>";
    } catch (Exception $e) {
        echo "<span style='color:red'>Error con edad $edad: " . $e->getMessage() . "</span><br>";
    }
}

echo "<p>Validación completada.</p>";
?>
<p style="margin-top: 2rem;"><a href="index.php">← Volver al menú</a></p>