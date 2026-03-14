<?php

function filtraCampo($campo)
{
    $campo = trim((string) $campo);
    $campo = stripslashes($campo);
    $campo = htmlspecialchars($campo, ENT_QUOTES, 'UTF-8');
    return $campo;
}

function validarCampoTexto($campo)
{
    $campoFiltrado = filtraCampo($campo);
    return $campoFiltrado !== '' && validarLargoCampo($campoFiltrado, 2);
}

function validarLargoCampo($campo, $longitud)
{
    return strlen(trim((string) $campo)) > $longitud;
}

function esNumeroValido($campo)
{
    $campoFiltrado = filtraCampo($campo);
    return $campoFiltrado !== '' && is_numeric($campoFiltrado);
}

function validaContrasena($campo)
{
    return !empty($campo) && validarLargoCampo($campo, 7);
}

function mostrarAlert($resultado)
{
    $exito = $resultado[0];
    $mensaje = $resultado[1];

    $alert = '<div class="alert ' . ($exito ? 'alert-success' : 'alert-warning') . '" role="alert">' . $mensaje . '</div>';
    echo $alert;
}