<?php

    trait CalculosCentroEstudos
    {
        public function numeroDeAprobados()
        {
            $aprobados = 0;
            foreach ($this->get_notas() as $nota) {
                if ($nota >= 5) {
                    $aprobados++;
                }
            }
            return $aprobados;
        }

        public function numeroDeSuspensos()
        {
            $suspensos = 0;
            foreach ($this->get_notas() as $nota) {
                if ($nota < 5) {
                    $suspensos++;
                }
            }
            return $suspensos;
        }

        public function notaMedia()
        {
            $notas = $this->get_notas();
            if (empty($notas)) {
                return 0;
            }
            return array_sum($notas) / count($notas);
        }
    }

    trait MostrarCalculos
    {
        public function saludo()
        {
            echo "Bienvenido al centro de calculo<br>";
        }

        public function showCalculusStudyCenter($aprobados, $suspensos, $media)
        {
            echo "<strong>Resumen de resultados</strong><br>";
            echo "Aprobados: " . $aprobados . "<br>";
            echo "Suspensos: " . $suspensos . "<br>";
            echo "Calificacion promedio: " . number_format($media, 2) . "<br>";
        }
    }

    class Notas
    {
        private $notas = [];

        public function get_notas()
        {
            return $this->notas;
        }

        public function set_notas($notas)
        {
            $this->notas = $notas;
        }

        public function toString()
        {
            $listaDeNotas = "";
            foreach ($this->get_notas() as $nota) {
                $listaDeNotas .= "[$nota]";
            }
            return $listaDeNotas;
        }
    }

    class NotasTrait extends Notas
    {
        use CalculosCentroEstudos;
        use MostrarCalculos;
    }

    $notas = new NotasTrait();
    $notas->set_notas([7, 5, 2, 8, 3, 10, 6, 0, 8, 7]);

    $notas->saludo();
    echo "Notas: " . $notas->toString() . "<br>";
    $notas->showCalculusStudyCenter(
        $notas->numeroDeAprobados(),
        $notas->numeroDeSuspensos(),
        $notas->notaMedia()
    );

?>