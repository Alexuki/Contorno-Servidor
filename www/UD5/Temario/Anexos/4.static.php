<?php
    class Alien {
        public $nombre;
        private static $numberOfAliens;

        public function __construct($nombre) {
            $this->nombre = $nombre;
            self::$numberOfAliens++;
        }

        public static function getNumberOfAliens() {
            return self::$numberOfAliens;
        }
    }


    $alien1 = new Alien("Alien 1");
    $alien2 = new Alien("Alien 2");
    $alien3 = new Alien("Alien 3");
    $alien4 = new Alien("Alien 4");
    $alien5 = new Alien("Alien 5");

    echo "Número de aliens: " . Alien::getNumberOfAliens();

?>