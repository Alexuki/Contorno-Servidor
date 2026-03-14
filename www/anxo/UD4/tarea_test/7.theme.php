<?php
    require_once "config.php";

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["go_back"])) {
        if(isLogged()) {
            goToPagePanel();
        }
        goToPageLogin();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["theme"])) {
        setcookie("theme", $_POST["theme"], time() + 86400 * 30, "/");
        $tema = $_POST["theme"];
    }
?>
<html lang="es" class="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="utf-8">
    <title>Cambiar tema</title>
    <style>
        body.claro { background: #f5f5f5; color: #333; }
        body.oscuro { background: #333; color: #f5f5f5; }
        .link-button {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            color: #0a58ca;
            text-decoration: underline;
            cursor: pointer;
            font: inherit;
        }
        .link-button:hover { color: #084298; }
    </style>
</head>
<body class="<?= htmlspecialchars($tema) ?>">
    <h1>Cambiar tema de la aplicación</h1>
        
    <p>Tema actual: <strong><?= htmlspecialchars($tema) ?></strong></p>
    
    <!-- Formulario -->
    <form method="post">
        <p>
            <label>
                <input type="radio" name="theme" value="claro" <?= $tema === 'claro' ? 'checked' : '' ?>>
                Tema claro
            </label>
        </p>
        <p>
            <label>
                <input type="radio" name="theme" value="oscuro" <?= $tema === 'oscuro' ? 'checked' : '' ?>>
                Tema oscuro
            </label>
        </p>
        <button type="submit">Guardar preferencia</button>
    </form>
    
    <form method="post" style="display:inline;">
        <button type="submit" name="go_back" value="1" class="link-button">Volver</button>
    </form>
</body>
</html>