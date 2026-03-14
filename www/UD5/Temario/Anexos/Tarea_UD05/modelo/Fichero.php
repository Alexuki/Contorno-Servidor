<?php

class Fichero
{
    public const FORMATOS = ['jpg', 'png', 'pdf'];
    public const MAX_SIZE = 20971520;

    private int $id;
    private string $nombre;
    private string $file;
    private string $descripcion;
    private int $tarea;

    public function __construct(
        int $id = 0,
        string $nombre = '',
        string $file = '',
        string $descripcion = '',
        int $tarea = 0
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->file = $file;
        $this->descripcion = $descripcion;
        $this->tarea = $tarea;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getTarea(): int
    {
        return $this->tarea;
    }

    public function setTarea(int $tarea): void
    {
        $this->tarea = $tarea;
    }

    public static function validarCampos(string $descripcion, array $upload): array
    {
        $errores = [];

        if (trim($descripcion) === '' || strlen(trim($descripcion)) < 3) {
            $errores['descripcion'] = 'La descripcion es obligatoria y debe tener al menos 3 caracteres.';
        }

        if (!isset($upload['error']) || (int) $upload['error'] !== UPLOAD_ERR_OK) {
            $errores['file'] = 'No se ha subido un fichero valido.';
            return $errores;
        }

        $nombre = (string) ($upload['name'] ?? '');
        $size = (int) ($upload['size'] ?? 0);
        $ext = strtolower((string) pathinfo($nombre, PATHINFO_EXTENSION));

        if (!in_array($ext, self::FORMATOS, true)) {
            $errores['file'] = 'Formato no permitido. Solo: ' . implode(', ', self::FORMATOS) . '.';
        }

        if ($size <= 0 || $size > self::MAX_SIZE) {
            $errores['size'] = 'El fichero supera el tamano maximo de ' . (self::MAX_SIZE / (1024 * 1024)) . 'MB.';
        }

        return $errores;
    }
}
