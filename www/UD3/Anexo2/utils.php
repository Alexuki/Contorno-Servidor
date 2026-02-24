<?php

function filtraCampo($campo)
{
    $campo = trim($campo);
    $campo = stripslashes($campo);
    $campo = htmlspecialchars($campo);
    return $campo;
}

function validarCampoTexto($campo)
{
    return (!empty(filtraCampo($campo) && validarLargoCampo($campo, 2)));
}

function validarLargoCampo($campo, $longitud)
{
    return (strlen(trim($campo)) > $longitud);
}

function esNumeroValido($campo)
{
    return (!empty(filtraCampo($campo) && is_numeric($campo)));
}

function validaContrasena($campo)
{
    return (!empty($campo) && validarLargoCampo($campo, 7));
}

function mostrarAlert($resultado) {
    $exito = $resultado[0];
    $mensaje = $resultado[1];
    

    $alert = '<div class="alert ' . ($exito ? 'alert-success' : 'alert-warning') . '" role="alert">' . $mensaje . '</div>';
    echo $alert;
}