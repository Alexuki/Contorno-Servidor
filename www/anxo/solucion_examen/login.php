<?php
// login.php
require_once 'models/Usuario.php';
require_once 'models/Auth.php';

$error = '';

/**
* IMPLEMENTAR POR EL ALUMNO
* Verifica que el login haya sido correcto y redirige a index.php en dicho caso.
* En caso contrario mostrará un mensaje de error: "Usuario o contraseña incorrectos".
*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (Auth::login($_POST['username'], $_POST['password'])) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Mini Villa Olímpica</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f0f9ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        h2 { text-align: center; color: #1e3a8a; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .error { 
            color: #dc2626; 
            text-align: center; 
            margin-bottom: 15px;
        }
        .info {
            background: #e0f2fe;
            padding: 10px;
            border-radius: 4px;
            font-size: 0.8em;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>🏔️ Acceso</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>