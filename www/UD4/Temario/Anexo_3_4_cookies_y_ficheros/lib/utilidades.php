<?php

/*
* Funciones para validación de campos, cambios de formato, etc.
*/

/**
 * Test de entrada en formularios. Elimina espacios y barras y convierte caracteres
 * especiales en entidades para evitar inyección de código.
 */
function test_input($datos)
{
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
}