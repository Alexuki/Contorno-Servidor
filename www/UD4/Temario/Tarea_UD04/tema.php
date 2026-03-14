<?php
require_once('auth.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tema = $_POST['tema'] ?? 'light';
    if (!in_array($tema, ['light', 'dark', 'auto'], true)) {
        $tema = 'light';
    }
    setcookie('tema', $tema, time() + (86400 * 30), '/');
}

$referer = $_SERVER['HTTP_REFERER'] ?? appBaseUrl() . 'index.php';
header('Location: ' . $referer);
exit();
