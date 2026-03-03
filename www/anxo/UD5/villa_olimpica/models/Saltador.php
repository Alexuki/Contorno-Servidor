<?php
// models/Saltador.php

class Saltador extends Deportista {
    private $tipoSalto;
    private $alturaMaxima;
    
    public function __construct($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce, $tipoSalto, $alturaMaxima, $id = null) {
        parent::__construct($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce, $id);
        $this->tipoSalto = $tipoSalto;
        $this->alturaMaxima = $alturaMaxima;
    }
    
    public function getTipoDeporte() {
        return 'salto';
    }
    
    public function getDetalles() {
        return array('tipo_salto' => $this->tipoSalto, 'altura_maxima' => $this->alturaMaxima);
    }
    
    public function getTipoSalto() { return $this->tipoSalto; }
    public function getAlturaMaxima() { return $this->alturaMaxima; }
}
?>