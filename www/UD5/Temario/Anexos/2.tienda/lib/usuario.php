<?php

/**
 * Entidad de dominio para representar un usuario de la tienda.
 */
class Usuario
{
    private $nombre;
    private $apellidos;
    private $edad;
    private $provincia;

    public function __construct($nombre, $apellidos, $edad, $provincia)
    {
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->edad = $edad;
        $this->provincia = $provincia;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getEdad()
    {
        return $this->edad;
    }

    public function getProvincia()
    {
        return $this->provincia;
    }
}