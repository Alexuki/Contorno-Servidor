<?php

    abstract class Persona {
        private $id;
        protected $nombre;
        protected $apellidos;

        abstract function __construct($id, $nombre, $apellidos);
        function getId() {
            return $this->id;
        }

        function setId($id) {
            $this->id = $id;
        }

        abstract function getNombre();
        abstract function getApellidos();
        abstract function setNombre($nombre);
        abstract function setApellidos($apellidos);
        abstract function accion();
    }


    class Usuario extends Persona {
        function __construct($id, $nombre, $apellidos) {
            parent::setId($id);
            $this->nombre = $nombre;
            $this->apellidos = $apellidos;
        }

        function getNombre() {
            return $this->nombre;
        }
        function getApellidos() {
            return $this->apellidos;
        }

        function setNombre($nombre) {
            $this->nombre = $nombre;
        }
        function setApellidos($apellidos) {
            $this->apellidos = $apellidos;
        }

        function accion() {
            echo "USUARIO: " . $this->getNombre() . " " . $this->getApellidos() . "<br>";
        }
    }

        class Administrador extends Persona {
        function __construct($id, $nombre, $apellidos) {
            parent::setId($id);
            $this->nombre = $nombre;
            $this->apellidos = $apellidos;
        }

        function getNombre() {
            return $this->nombre;
        }
        function getApellidos() {
            return $this->apellidos;
        }

        function setNombre($nombre) {
            $this->nombre = $nombre;
        }
        function setApellidos($apellidos) {
            $this->apellidos = $apellidos;
        }

        function accion() {
            echo "ADMINISTRADOR: " . $this->getNombre() . " " . $this->getApellidos() . "<br>";
        }
    }

    $usuario = new Usuario(1, "Alejandro", "Martínez Corral");
    $admin = new Administrador(200, "Admin", "Admin 2");

    $usuario->accion();
    $admin->accion();

?>