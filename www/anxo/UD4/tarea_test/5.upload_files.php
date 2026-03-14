<?php
    require_once "config.php";
    requireLogin();
?>
<html lang="es" class="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="utf-8">
    <title>Subir archivo</title>
    <style>
        body.claro { background: #f5f5f5; color: #333; }
        body.oscuro { background: #333; color: #f5f5f5; }
    </style>
</head>

<body class="<?= htmlspecialchars($tema) ?>">
    <h1>Subir archivo</h1>
    
    <?php if (getRol() !== 'admin'): ?>
        <p><em>Nota: Como usuario normal, solo puedes subir JPG o PNG.</em></p>
    <?php endif; ?>
    
    <!-- Formulario con enctype obligatorio -->
    <form action="6.upload_files_process.php" method="post" enctype="multipart/form-data">
        <p>
            <label>Selecciona archivo:</label><br>
            <input type="file" name="file" required>
        </p>
        <p><small>Máximo: 500KB</small></p>
        <button type="submit">Subir</button>
    </form>
    
    <p><a href="4.panel.php">Volver al panel</a></p>
</body>
</html>