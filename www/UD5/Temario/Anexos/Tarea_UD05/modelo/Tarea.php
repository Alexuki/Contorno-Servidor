<?php

class Tarea
{
    private int $id;
    private string $titulo;
    private string $descripcion;
    private string $estado;
    private int $usuario;

    public function __construct(
        int $id = 0,
        string $titulo = '',
        string $descripcion = '',
        string $estado = '',
        int $usuario = 0
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->usuario = $usuario;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getUsuario(): int
    {
        return $this->usuario;
    }

    public function setUsuario(int $usuario): void
    {
        $this->usuario = $usuario;
    }
}
