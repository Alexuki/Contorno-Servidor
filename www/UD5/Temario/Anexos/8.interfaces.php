<?php

    interface CalculosCentroEstudios {
        public function numeroDeAprobados();
        public function numeroDeSuspensos();
        public function notaMedia();
    }


    class Notas {
        private $notas;

        public function get_notas() {
            return $this->notas;
        }

        public function set_notas($notas) {
            $this->notas = $notas;
        }

        public function toString()
        {
            $listaDeNotas = "";
            foreach ($this-> get_notas() as $nota) {
                $listaDeNotas .= "[$nota]";
            }
            return $listaDeNotas;
        }
    }

    class NotasDaw extends Notas implements CalculosCentroEstudios {
        public function numeroDeAprobados() {
            $aprobados = 0;
            foreach ($this->get_notas() as $nota) {
                if ($nota >= 5) {
                    $aprobados++;
                }
            }
            return $aprobados;
        }
        public function numeroDeSuspensos() {
            $suspensos = 0;
            foreach ($this->get_notas() as $nota) {
                if ($nota < 5) {
                    $suspensos++;
                }
            }
            return $suspensos;
        }
        public function notaMedia() {
            $notas = $this->get_notas();
            if (empty($notas)) {
                return 0;
            }
            return array_sum($notas) / count($notas);
        }
    }

    $notas = new NotasDaw();
    $notas->set_notas([7, 5, 2, 8, 3, 10, 6, 0, 8, 7, 9, 1, 10, 8, 8, 4, 7, 8, 10, 9]);
    echo "Notas: " . $notas->toString() . "<br>";
    echo "Aprobados: " . $notas->numeroDeAprobados() . "<br>";
    echo "Suspensos: " . $notas->numeroDeSuspensos() . "<br>";
    echo "Nota media: " . $notas->notaMedia() . "<br>";

?>