<?php
    session_start();

    $usuarios = [
        "raquel" => [
            "clave" => "raquel123",
            "rol" => "admin"
        ],
        "sara" => [
            "clave" => "sara123",
            "rol" => "user"
        ]
    ];

    function isLogged() {
        return isset($_SESSION["nombre"]);
    }

    function getRol() {
        global $usuarios;
        $nombre = $_SESSION["nombre"] ?? null;

        if($nombre) {
            $rol = $usuarios[$nombre]["rol"];
            return htmlspecialchars($rol);
        }
        return "";
    }

    function requireLogin() {
        if(!isLogged()) {
            header("Location: 1.login.php");
        }
    }

    function requireAdmin() {
        requiereLogin();
        if(getRol() != "admin") {
            header("Location: 4.panel.php?error='unauthorized'");
        }
    }

    function goToPageLogin($errorMessage = "") {
        header("Location: 1.login.php" .( $errorMessage != "" ? "?error=$errorMessage" : ""));
        exit();
    }

    function goToPagePanel() {
        header("Location: 4.panel.php");
        exit();
    }

    function goToPageTheme() {
        header("Location: 7.theme.php");
        exit();
    }

    $tema = $_COOKIE["theme"] ?? "claro";

?>