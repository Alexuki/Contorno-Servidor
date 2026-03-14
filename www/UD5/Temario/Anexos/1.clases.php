<?php
    class Contacto {
        private $nombre;
        private $apellido;
        private $telefono;

        function __construct($nombre, $apellido, $telefono) {
            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->telefono = $telefono;
        }

        function __destruct() {
            echo "Se destruye el objeto con estas propiedades: <br>";
            $this->mostrarInformacion();
            echo "<br>";
        }

        public function getNombre() {
            return $this->nombre;
        }
        public function getApellido() {
            return $this->apellido;
        }
        public function getTelefono() {
            return $this->telefono;
        }

        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }
        public function setApellido($apellido) {
            $this->apellido = $apellido;
        }
        public function setTelefono($telefono) {
            $this->telefono = $telefono;
        }

        public function mostrarInformacion() {
            echo "$this->nombre {$this->apellido}, teléfono: $this->telefono";
        }
    }

    $contacto1 = new Contacto("Alba", "López", 123456789);
    $contacto2 = new Contacto("Antonio", "Pérez", 788671340);
    $contacto3 = new Contacto("María", "Castro", 642157892);
    echo "Contacto 1: " . $contacto1->getNombre() . " " . $contacto1->getApellido() . ", teléfono: " . $contacto1->getTelefono() . "<br>";
    echo "Contacto 2: " . $contacto2->getNombre() . " " . $contacto2->getApellido() . ", teléfono: " . $contacto2->getTelefono() . "<br>";
    echo "Contacto 3: " . $contacto3->getNombre() . " " . $contacto3->getApellido() . ", teléfono: " . $contacto3->getTelefono() . "<br>";
    echo "<br>";
?>