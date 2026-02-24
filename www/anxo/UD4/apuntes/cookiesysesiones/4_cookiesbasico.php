<?php
/* ========= CREAR / MODIFICAR COOKIE ========= */
$cookie_name = 'usuario';
$cookie_value = 'Sabela';
setcookie($cookie_name, $cookie_value, time() + 86400 * 30, "/"); // 30 días

/* ========= MODIFICAR ========= */
if (isset($_GET['modificar'])) {
    setcookie($cookie_name, 'Ivan', time() + 86400 * 30, '/');
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

/* ========= BORRAR ========= */
if (isset($_GET['borrar'])) {
    setcookie($cookie_name, '', time() - 3600, '/'); // fecha pasada
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>4. Práctica básica de cookies</title></head>
<body>
    <h1>Cookies</h1>

    <!-- Leer cookie -->
    <?php if (isset($_COOKIE[$cookie_name])): ?>
        <p>Cookie <em><?= $cookie_name ?></em> existe y vale: <b><?= htmlspecialchars($_COOKIE[$cookie_name]) ?></b></p>
    <?php else: ?>
        <p>Cookie <em><?= $cookie_name ?></em> no existe o fue borrada.</p>
    <?php endif; ?>

    <!-- Comprobar si el navegador acepta cookies -->
    <p>¿Cookies habilitadas? <b><?= (count($_COOKIE) > 0) ? 'Sí' : 'No' ?></b></p>

    <!-- Enlaces rápidos -->
    <ul>
        <li><a href="?modificar=1">Cambiar valor de la cookie (recarga)</a></li>
        <li><a href="?borrar=1">Borrar cookie (caducando)</a></li>
        <li><a href="1_iniciaryleer.php">Ir a sesiones</a></li>
    </ul>


</body>
</html>