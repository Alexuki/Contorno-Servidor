<?php
require_once 'config.php';

// Si ya está logueado, redirigir al panel
if (estaLogueado()) {
    header('Location: 4_panel.php');
    exit();
}
?>
<!doctype html>
<html lang="es" class="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <style>
        body.claro { background: #f5f5f5; color: #333; }
        body.oscuro { background: #333; color: #f5f5f5; }
        .error { color: red; }
    </style>
</head>
<body class="<?= htmlspecialchars($tema) ?>">
    <h1>Iniciar sesión</h1>
    
    <?php if (isset($_GET['error'])): ?>
        <p class="error">Usuario o contraseña incorrectos</p>
    <?php endif; ?>
    
    <form action="2_loginAuth.php" method="post">
        <p>
            <label>Usuario:</label><br>
            <input type="text" name="usuario" required>
        </p>
        <p>
            <label>Contraseña:</label><br>
            <input type="password" name="clave" required>
        </p>
        <button type="submit">Entrar</button>
    </form>
    
    <p><a href="7_temaForm.php">Cambiar tema (claro/oscuro)</a></p>
</body>
</html>