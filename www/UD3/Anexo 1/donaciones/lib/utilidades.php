<?php

/**
 * Función para evitar inyección de código en los formularios.
 */
function test_input($datos)
{
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
}

/**
 * Función para formatear mensajes acumulados al realizar
 * operaciones en la BBDD.
 */
function get_mensajes_html_format($mensajes)
{
    $resultado = "";

    if (count($mensajes) > 0) {
        foreach ($mensajes as $mensaje) {
            if ($mensaje[0] == "error") {
                $resultado .= '<div class="alert alert-danger" role="alert">' . $mensaje[1] . '</div>';
            } elseif ($mensaje[0] == "success") {
                $resultado .= '<div class="alert alert-success" role="alert">' . $mensaje[1] . '</div>';
            }
        }
    }

    return $resultado;
}
