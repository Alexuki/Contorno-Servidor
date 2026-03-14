<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

const APP_BASE_PATH = '/UD4/Temario/Tarea_UD04/';

function appBaseUrl()
{
    return 'http://' . $_SERVER['HTTP_HOST'] . APP_BASE_PATH;
}

function redirectTo($relativePath)
{
    header('Location: ' . appBaseUrl() . ltrim($relativePath, '/'));
    exit();
}

function getTema()
{
    $tema = $_COOKIE['tema'] ?? 'light';
    if (!in_array($tema, ['light', 'dark', 'auto'], true)) {
        return 'light';
    }
    return $tema;
}

function getUsuarioSesion()
{
    return $_SESSION['usuario'] ?? null;
}

function usuarioAutenticado()
{
    return getUsuarioSesion() !== null;
}

function esAdmin()
{
    $usuario = getUsuarioSesion();
    return $usuario !== null && (int) $usuario['rol'] === 1;
}

function requireLogin($requireAdmin = false)
{
    if (!usuarioAutenticado()) {
        redirectTo('login.php');
    }

    // Comprobación usuarios autenticados intentando acceder a página que es solo para admin
    if ($requireAdmin && !esAdmin()) {
        redirectTo('index.php'); // Si no es admin, redidirgir al index.php
    }
}

function puedeGestionarTarea($idUsuarioTarea)
{
    $usuario = getUsuarioSesion();
    if ($usuario === null) {
        return false;
    }

    return esAdmin() || (int) $usuario['id'] === (int) $idUsuarioTarea;
}
