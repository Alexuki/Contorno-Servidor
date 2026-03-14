<?php
    class Participante {
        private $nombre;
        private $edad;

        public function __construct($nombre, $edad)
        {
            $this->nombre = $nombre;
            $this->edad = $edad;
        }

        public function getNombre() {
            return $this->nombre;
        }
        public function setNombre($nombre) {
            return $this->nombre = $nombre;
        }
        public function getEdad() {
            return $this->edad;
        }
        public function setEdad($edad) {
            return $this->edad = $edad;
        }
    }

    class Jugador extends Participante {
        private $posicion;

        public function __construct($nombre, $edad, $posicion) {
            parent::__construct($nombre, $edad);
            $this->posicion = $posicion;
        }

        public function getPosicion() {
            return $this->posicion;
        }

        public function setPosicion($posicion) {
            $this->posicion = $posicion;
        }

        public function info() {
            echo self::class . " " . $this->getNombre() . ", edad: " . $this->getEdad() . ", posición: " . $this->posicion . "<br>";
        }
    }

    class Arbitro extends Participante {
        private $añosArbitraje;

        public function __construct($nombre, $edad, $añosArbitraje) {
            parent::__construct($nombre, $edad);
            $this->añosArbitraje = $añosArbitraje;
        }

        public function getAñosArbitraje() {
            return $this->añosArbitraje;
        }

        public function setAñosArbitraje($añosArbitraje) {
            $this->añosArbitraje = $añosArbitraje;
        }

        public function info() {
            echo self::class . " " . $this->getNombre() . ", edad: " . $this->getEdad() . ", años arbitraje: " . $this->añosArbitraje . "<br>";
        }
    }

    $j1 = new Jugador("Alex", 39, "centro");
    $j2 = new Jugador("María", 25, "delantero");
    $a1 = new Arbitro("Pepe", 42, 5);
    $a2 = new Arbitro("Eva", 50, 10);

    echo "Creados participantes: <br>";
    $j1->info();
    $j2->info();
    $a1->info();
    $a2->info();

    echo "<br>Cambio de propiedades con métodos públicos: <br>";
    $j1->setNombre("Alex Nuevo");
    $j2->setEdad(21);
    $j2->setPosicion("portero");
    $a1->setNombre("Alfonso");
    $a2->setEdad(19);
    $a2->setAñosArbitraje(1);

    $j1->info();
    $j2->info();
    $a1->info();
    $a2->info();

?>