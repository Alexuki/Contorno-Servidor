<?php
session_start(); // ¡imprescindible para acceder a $_SESSION!
?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>2. Usar sesión en otra página</title></head>
<body>
    <h1>Otra página</h1>
    <p>ID de sesión actual: <code><?= session_id() ?></code></p>
    <p>Color favorito (recuperado): <b><?= htmlspecialchars($_SESSION['favcolor'] ?? 'no definido') ?></b></p>
    <p>Animal favorito (recuperado): <b><?= htmlspecialchars($_SESSION['favanimal'] ?? 'no definido') ?></b></p>

    <ul>
        <li><a href="1_iniciaryleer.php">Volver a la primera página</a></li>
        <li><a href="3_modificarydestruir.php">Modificar o destruir sesión</a></li>
    </ul>
</body>
</html>