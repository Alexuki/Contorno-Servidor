<?php

// Crear cookie
$cookie_name = "user";
$cookie_value = "alex";
$cookie_max_time = time() + (24 * 60 * 60);
$cookie_path = "/";

setcookie($cookie_name, $cookie_value, $cookie_max_time, $cookie_path);

// Modificar cookie
if (isset($_GET["modificar"])) {
    setcookie($cookie_name, "Cookie modificada",  $cookie_max_time, $cookie_path);
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}

// Borrar cookie
if (isset($_GET["borrar"])) {
    setcookie($cookie_name, "Cookie modificada",  time() - 1, $cookie_path);
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}
?>

<html lang="es">

<head>
    <meta charset="utf-8">
    <title>1c. Cookies</title>
</head>

<body>
    <h1>Cookies</h1>

    <?php if (isset($_COOKIE[$cookie_name])): ?>
        <p>Cookie <?= $cookie_name ?> existe con el valor: <?= htmlspecialchars($_COOKIE[$cookie_name]) ?></p>
    <?php else: ?>
        <p>Cookie <?= $cookie_name ?> no existe </p>
    <?php endif; ?>

    <ul>
        <li><a href="?modificar=1">Cambiar valor de la cookie (recarga)</a></li>
        <li><a href="?borrar=1">Borrar cookie (caducando)</a></li>
        <li><a href="1.sesiones.php">Volver a página principal</a></li>
    </ul>

</body>

</html>