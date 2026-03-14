<?php
require_once('auth.php');
if (usuarioAutenticado()) {
    redirectTo('index.php');
}
$tema = getTema();
$error = isset($_GET['error']) ? 'Credenciales incorrectas.' : '';
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="<?php echo $tema; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto" style="max-width: 420px;">
        <form action="loginAuth.php" method="POST" class="card p-4 shadow-sm">
            <h1 class="h4 mb-3 fw-normal">Iniciar sesion</h1>

            <?php if ($error !== '') { ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php } ?>

            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required>
                <label for="username">Usuario</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contrasena" required>
                <label for="contrasena">Contrasena</label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Entrar</button>
        </form>
    </main>
</body>
</html>
