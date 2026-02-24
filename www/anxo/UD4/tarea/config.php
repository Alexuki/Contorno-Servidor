<?php
// =====================================
// CONFIGURACIÓN GLOBAL
// ====================================

// Iniciar sesión en todas las páginas
session_start();

// Crear rol 
$usuarios = [
    'raquel' => ['clave' => 'raquel123', 'rol' => 'admin'],
    'sara'  => ['clave' => 'sara123',  'rol' => 'user']
];

// Función para comprobar si está logueado
function estaLogueado() {
    return isset($_SESSION['nombre']);
}

// Función para obtener rol
function getRol() {
    return $_SESSION['rol'] ?? '';
}

// Función para redireccionar si no hay sesión 
function requiereLogin() {
    if (!estaLogueado()) {
        header('Location: 1_login.php');
        exit();
    }
}

// Función para redireccionar si no tiene permiso
function requiereAdmin() {
    if (getRol() !== 'admin') {
        header('Location: 4_panel.php?error=sin_permiso');
        exit();
    }
}

// Leer cookie de tema 
$tema = $_COOKIE['tema'] ?? 'claro'; 
?>