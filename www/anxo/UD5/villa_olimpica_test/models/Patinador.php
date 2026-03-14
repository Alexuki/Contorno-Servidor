<?php
// models/Patinador.php

class Patinador extends Deportista {
    private $especialidad;
    private $distancia;
    
    public function __construct($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce, $especialidad, $distancia, $id = null) {
        parent::__construct($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce, $id);
        $this->especialidad = $especialidad;
        $this->distancia = $distancia;
    }
    
    public function getTipoDeporte() {
        return 'patinaje';
    }
    
    public function getDetalles() {
        return array('especialidad' => $this->especialidad, 'distancia' => $this->distancia);
    }
    
    public function getEspecialidad() { return $this->especialidad; }
    public function getDistancia() { return $this->distancia; }
}
?>