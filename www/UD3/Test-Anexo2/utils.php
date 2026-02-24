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
    $campo = filtraCampo($campo);
    return is_string($campo) && strlen($campo) > 0;
}

function validarLargoCampo($campo, $longitud)
{
    $campo = filtraCampo($campo);
    return strlen($campo) > $longitud;
}

function esNumeroValido($campo)
{
    $campo = filtraCampo($campo); 
    return !empty($campo) && is_numeric($campo);
}

function validaContrasena($campo)
{
    return !empty($campo) && validarLargoCampo($campo, 7);
}