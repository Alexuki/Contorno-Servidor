<?php
    require_once "config.php";

    if(!isLogged()) {
        goToPageLogin("no_logged");
    }
?>

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
    <h1>Bienvenida,  <?= htmlspecialchars($_SESSION["nombre"]) ?></h1>
    <p>Tu rol: <?= getRol() ?></strong></p>

    <?php if(isset($_GET["error"])): ?>
        <span class="error"><?= $_GET["error"]?></span>
    <?php endif ?>
    
    <?php if(getRol() == "admin"): ?>
        <div class="admin">
            <h2>Zona de Administrador</h2>
            <ul>
                <li><a href="5.upload_files.php">Subir archivos</a> (todos los formatos)</li>
            </ul>
        </div>
    <?php else: ?>
        <div class="user">
            <h2>Zona de Usuario</h2>
            <ul>
                <li><a href="5.upload_files.php">Subir archivos</a> (solo imágenes)</li>
            </ul>
        </div>
    <?endif ?>
    
    <hr>
    <p>
        <a href="7.theme.php">Cambiar tema</a> | 
        <a href="3.logout.php">Cerrar sesión (logout)</a>
    </p>
</body>
</html>