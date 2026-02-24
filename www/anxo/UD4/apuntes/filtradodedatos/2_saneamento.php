<?php
// Sanear email (quita caracteres inválidos)
$email = '(usuario@example.org)';
$emailLimpio = filter_var($email, FILTER_SANITIZE_EMAIL);

echo "<h2>Email</h2>";
echo "<p>Original: <code>" . htmlspecialchars($email) . "</code></p>";
echo "<p>Saneado: <code>" . htmlspecialchars($emailLimpio) . "</code></p>";

echo "<hr>";

// Sanear URL (codifica caracteres especiales)
$url = 'https://ejemplo.com/página con espacios.php?nombre=José&edad=25';
$urlLimpia = filter_var($url, FILTER_SANITIZE_URL);

echo "<h2>URL</h2>";
echo "<p>Original: <code>" . htmlspecialchars($url) . "</code></p>";
echo "<p>Saneada: <code>" . htmlspecialchars($urlLimpia) . "</code></p>";
?>