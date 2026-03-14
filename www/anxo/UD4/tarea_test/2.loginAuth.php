<?php

    require_once "config.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"] ?? "";
        $password = $_POST["password"] ?? "";

        $valid = isset($usuarios[$name]) && $usuarios[$name]["clave"] === $password;

        if($valid) {
            $_SESSION["nombre"] = $name;
            header("Location: 4.panel.php");
            exit();
        }  
    } 

    goToPageLogin("failed_login");
?>