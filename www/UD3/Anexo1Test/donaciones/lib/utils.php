<?php

function ajustarInput($valor) {
    if (is_string($valor)) {
        $valor = trim($valor);
        $valor = stripslashes($valor);
        $valor = htmlspecialchars($valor);
    }
    
    return $valor;
}

function mostrarMensajeResultadoSql($mensajes) {

    $texto = "";

    foreach ($mensajes as $m) {
        $exito = $m[0];
        $mensaje = $m[1];

        if ($exito) {
            $texto .= '<div class="alert alert-success" role="alert">' . $mensaje . '</div>';
        } else {
            $texto .= '<div class="alert alert-danger" role="alert">' . $mensaje . '</div>';
        }
    }
    return $texto;
    
}