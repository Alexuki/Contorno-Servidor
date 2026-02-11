<?php
require_once 'config.php';

// Recibir datos del formulario
$nombre = $_POST['usuario'] ?? '';
$contraseña = $_POST['clave']   ?? '';

// Validar credenciales
if (isset($usuarios[$nombre]) && $usuarios[$nombre]['clave'] === $contraseña) {
    // Crear variables de sesión
    $_SESSION['nombre'] = $nombre;
    $_SESSION['rol'] = $usuarios[$nombre]['rol'];
    
    // Redirigir al panel
    header('Location: 4_panel.php');
    exit();
} else {
    // Login fallido
    header('Location: 1_login.php?error=1');
    exit();
}
?>