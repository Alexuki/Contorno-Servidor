<?php
require_once 'config.php';

// Redireccionar si no hay sesión 
requiereLogin();
?>
<!doctype html>
<html lang="es" class="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="utf-8">
    <title>Panel de control</title>
    <style>
        body.claro { background: #f5f5f5; color: #333; }
        body.oscuro { background: #333; color: #f5f5f5; }
        .admin { background: gold; padding: 10px; }
        .user { background: lightblue; padding: 10px; }
    </style>
</head>
<body class="<?= htmlspecialchars($tema) ?>">
    <h1>Bienvenida, <?= htmlspecialchars($_SESSION['nombre']) ?></h1>
    <p>Tu rol: <strong><?= htmlspecialchars($_SESSION['rol']) ?></strong></p>
    
    <?php if (isset($_GET['error']) && $_GET['error'] === 'sin_permiso'): ?>
        <p style="color: red;">No tienes permiso para acceder a esa zona.</p>
    <?php endif; ?>
    
    <!-- Filtrado de funcionalidades por rol -->
    <?php if ($_SESSION['rol'] === 'admin'): ?>
        <div class="admin">
            <h2>Zona de Administrador</h2>
            <ul>
                <li><a href="5_subidaFichForm.php">Subir archivos</a> (todos los formatos)</li>
                <li><a href="#">Gestionar usuarios</a></li>
                <li><a href="#">Ver logs del sistema</a></li>
            </ul>
        </div>
    <?php else: ?>
        <div class="user">
            <h2>Zona de Usuario</h2>
            <ul>
                <li><a href="5_subidaFichForm.php">Subir archivos</a> (solo imágenes)</li>
                <li><a href="#">Ver mi perfil</a></li>
            </ul>
        </div>
    <?php endif; ?>
    
    <hr>
    <p>
        <a href="7_temaForm.php">Cambiar tema</a> | 
        <a href="3_logout.php">Cerrar sesión (logout)</a>
    </p>
</body>
</html>