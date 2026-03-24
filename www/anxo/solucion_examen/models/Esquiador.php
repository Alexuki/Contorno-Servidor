<?php
// models/Esquiador.php

class Esquiador extends Deportista {
    private $disciplina;
    private $tipoEsqui;
    
    public function __construct($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce, $disciplina, $tipoEsqui, $id = null) {
        parent::__construct($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce, $id);
        $this->disciplina = $disciplina;
        $this->tipoEsqui = $tipoEsqui;
    }
    
    public function getTipoDeporte() {
        return 'esqui';
    }
    
    public function getDetalles() {
        return array(
            'disciplina' => $this->disciplina,
            'tipo_esqui' => $this->tipoEsqui
        );
    }
    
    public function getDisciplina() { return $this->disciplina; }
    public function getTipoEsqui() { return $this->tipoEsqui; }

     public function calcularPuntuacion() {
        if ($this->tipoEsqui == 'libre') {
            // 60 multiplicado por el total de todas las medallas
            return 60 * $this->getTotalMedallas();
        } else {
            // Tipo clásico: solo oro y plata cuentan, con pesos diferentes
            return (30 * $this->getMedallasOro()) + (20 * $this->getMedallasPlata());
        }
    }
}
?>