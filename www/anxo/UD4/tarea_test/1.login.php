<?php

    require_once "config.php";

    if(isLogged()) {
        goToPagePanel();
    }
?>

<html lang="es" class="<?= htmlspecialchars($tema) ?>">
    <head>
        <meta charset=utf-8>
        <title>Login</title>
        <style>
            body.claro { background: #f5f5f5; color: #333; }
            body.oscuro { background: #333; color: #f5f5f5; }
            .error { color: red; }
        </style>
    </head>

    <body class="<?= htmlspecialchars($tema) ?>">
        <h1>Login</h1>

        <?php if(isset($_GET["error"])): ?>
            <span class="error"><?= $_GET["error"] ?></span>
        <?php endif ?>

        <form action="2.loginAuth.php" method="post">
            <p>
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" required>
            </p>
            <p>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </p>
            <button type="submit">Entrar</button>
        </form>

        <p><a href="7.theme.php">Cambiar tema</a></p>

    </body>
</html>