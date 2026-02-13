<?php
// Validar email
$email = 'usuario@example.com';

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email válido: $email";
} else {
    echo "Email inválido";
}

echo "<br>";

// Validar número con rango (18-65)
$edad = 73;
$opciones = ['options' => ['min_range' => 18, 'max_range' => 65]];

if (filter_var($edad, FILTER_VALIDATE_INT)) {
    if(filter_var($edad, FILTER_VALIDATE_INT, $opciones)) {
        echo "Edad válida: $edad";
    } else {
        echo "Edad fuera de rango";
    }
} else {
    echo "Edad no válida";
}
?>