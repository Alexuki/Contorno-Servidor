<?php
require_once('auth.php');
require_once('modelo/pdo.php');
require_once('utils.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('login.php');
}

$username = filtraCampo($_POST['username'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

$resultado = autenticarUsuario($username, $contrasena);
if ($resultado[0]) {
    $_SESSION['usuario'] = $resultado[1];
    redirectTo('index.php');
}

redirectTo('login.php?error=1');
