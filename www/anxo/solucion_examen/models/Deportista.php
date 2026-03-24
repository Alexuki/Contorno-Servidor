<?php
// models/Deportista.php
// Clase abstracta base para todos los deportistas

abstract class Deportista {
    protected $id;
    protected $nombre;
    protected $apellidos;
    protected $pais;
    protected $edad;
    protected $genero;
    protected $medallasOro;
    protected $medallasPlata;
    protected $medallasBronce;
    
    public function __construct($nombre, $apellidos, $pais, $edad, $genero, $oro, $plata, $bronce, $id = null) {
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->pais = $pais;
        $this->edad = $edad;
        $this->genero = $genero;
        $this->id = $id;
        $this->medallasOro = $oro;
        $this->medallasPlata = $plata;
        $this->medallasBronce = $bronce;
    }
    
    // Getters básicos
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getPais() { return $this->pais; }
    public function getEdad() { return $this->edad; }
    public function getGenero() { return $this->genero; }
    public function getMedallasOro() { return $this->medallasOro; }
    public function getMedallasPlata() { return $this->medallasPlata; }
    public function getMedallasBronce() { return $this->medallasBronce; }
    public function getNombreCompleto() { return $this->nombre . " " . $this->apellidos; }

    public function getTotalMedallas() {
        return $this->medallasOro + $this->medallasPlata + $this->medallasBronce;
    }
    
    // Método abstracto que deben implementar las clases hijas
    abstract public function getTipoDeporte();
    
    // Método para obtener detalles específicos (array asociativo)
    abstract public function getDetalles();

    /**
     * --- EXAMEN --- 
     * Método abstracto: cada deportista calcula su puntuación según disciplina
     * Se implementa de forma diferente en cada clase hija
     */
    abstract public function calcularPuntuacion();
}
?>