<?php
// models/Usuario.php
// Clase simple que representa un usuario del sistema

class Usuario {
    private $username;
    private $password;
    private $rol;
    
    public function __construct($username, $password, $rol) {
        $this->username = $username;
        $this->password = $password;
        $this->rol = $rol;
    }
    
    public function getUsername() { return $this->username; }
    public function getRol() { return $this->rol; }
    
    public function verificarPassword($pass) {
        return $this->password == $pass;
    }
}
?>