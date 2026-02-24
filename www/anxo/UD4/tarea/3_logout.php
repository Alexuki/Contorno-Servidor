<?php
require_once 'config.php';

// Destruir sesión
session_unset();
session_destroy();

// Redirigir al login
header('Location: 1_login.php');
exit();
?>