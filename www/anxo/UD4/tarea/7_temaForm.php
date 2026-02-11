<?php
require_once 'config.php';

// Procesar cambio de tema (creación de cookie)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tema'])) {
    if ($_POST['tema'] === 'oscuro') {
        $nuevoTema = 'oscuro';
    } else {
        $nuevoTema = 'claro';
    }
    
    // Crear cookie por 30 días
    setcookie('tema', $nuevoTema, time() + 86400 * 30, '/');
    
    // Actualizar variable para esta carga
    $tema = $nuevoTema;
    
    $mensaje = "Tema cambiado a: " . $nuevoTema;
}

$temaActual = $tema; // 'claro' u 'oscuro' (leído de config.php)
?>
<!doctype html>
<html lang="es" class="<?= htmlspecialchars($temaActual) ?>">
<head>
    <meta charset="utf-8">
    <title>Cambiar tema</title>
    <style>
        body.claro { background: #f5f5f5; color: #333; }
        body.oscuro { background: #333; color: #f5f5f5; }
    </style>
</head>
<body class="<?= htmlspecialchars($temaActual) ?>">
    <h1>Cambiar tema de la aplicación</h1>
    
    <?php if (isset($mensaje)): ?>
        <p><strong><?= htmlspecialchars($mensaje) ?></strong></p>
    <?php endif; ?>
    
    <p>Tema actual: <strong><?= htmlspecialchars($temaActual) ?></strong></p>
    
    <!-- Formulario -->
    <form method="post">
        <p>
            <label>
                <input type="radio" name="tema" value="claro" <?= $temaActual === 'claro' ? 'checked' : '' ?>>
                Tema claro
            </label>
        </p>
        <p>
            <label>
                <input type="radio" name="tema" value="oscuro" <?= $temaActual === 'oscuro' ? 'checked' : '' ?>>
                Tema oscuro
            </label>
        </p>
        <button type="submit">Guardar preferencia</button>
    </form>
    
    <p><a href="<?= estaLogueado() ? '4_panel.php' : '1_login.php' ?>">Volver</a></p>
</body>
</html>