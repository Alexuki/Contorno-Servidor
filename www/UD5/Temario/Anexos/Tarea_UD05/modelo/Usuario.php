<?php

class Usuario
{
    private int $id;
    private string $username;
    private string $nombre;
    private string $apellidos;
    private string $contrasena;
    private int $rol;

    public function __construct(
        int $id = 0,
        string $username = '',
        string $nombre = '',
        string $apellidos = '',
        string $contrasena = '',
        int $rol = 0
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getContrasena(): string
    {
        return $this->contrasena;
    }

    public function setContrasena(string $contrasena): void
    {
        $this->contrasena = $contrasena;
    }

    public function getRol(): int
    {
        return $this->rol;
    }

    public function setRol(int $rol): void
    {
        $this->rol = $rol;
    }
}
