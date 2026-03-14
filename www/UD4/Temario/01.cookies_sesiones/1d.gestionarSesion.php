<?php
    session_start();

    // Cambio de variable
    $_SESSION["color"] = "azul";

    // Borrar variable
    unset($_SESSION["fruta"]);

    // Eliminar sesión (iniciado desde el botón)
    if(isset($_GET["destruirSesion"])) {
        session_unset();    // Eliminar variables
        session_destroy();  // Eliminar fichero de sesión
        /**
         * Redirige al usuario a la misma página actual y termina la ejecución del script.
         * 
         * La redirección se realiza usando header("Location: ...") hacia PHP_SELF (la página actual).
         * Se utiliza exit() inmediatamente después para:
         * - Detener la ejecución del código PHP restante en el script actual
         * - Asegurar que el navegador procese la redirección sin ejecutar más lógica
         * - Evitar que se envíen datos adicionales al cliente después del header de redirección
         * 
         * Caso de uso común: Después de procesar un formulario POST, se redirige a GET 
         * para evitar el reenvío duplicado de datos si el usuario recarga la página.
         */
        header("Location: " . $_SERVER["PHP_SELF"]); // Recargar
        exit();
    }
?>

<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>1d. Gestionar sesión</title>
    </head>
    <body>
        <h1>Otra página con la sesión de 1.Sesiones</h1>
        <p>ID de la sesión: <?= session_id() ?></p>
         <p>Color (modificado ahora): <?= htmlspecialchars($_SESSION["color"] ?? "Variable no definida") ?></p>
        <p>Fruta (eliminado): <?= $_SESSION["fruta"] ?? "Variable no definida" ?></p>

        <a href="?destruirSesion=cualquierValor">Destruir sesión</a>
        <a href="1.sesiones.php">Volver a página principal</a>
        
    </body>
</html>
