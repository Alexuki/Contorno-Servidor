<?php
// config/Database.php

class Database {
    private static $instance = null;
    private static $conn = null;
    
    private static $servername = "db";
    private static $username = "root";
    private static $password = "test";
    private static $dbname = "solucion_minivilla";
    
    private function __construct() {
        try {
            // Crear conexión inicial
            self::$conn = new PDO("mysql:host=" . self::$servername, self::$username, self::$password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Crear la base de datos
            $sql = "CREATE DATABASE IF NOT EXISTS " . self::$dbname;
            self::$conn->exec($sql);
            
            // Reconectar con la base de datos
            self::$conn = new PDO("mysql:host=" . self::$servername . ";dbname=" . self::$dbname, self::$username, self::$password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Crear la tabla única para todos los deportistas
            $this->createTable();
            
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage() . "<br>";
        }
    }
    
    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$conn;
    }
    
    public static function close() {
        self::$conn = null;
        self::$instance = null;
    }
    
    // UNA SOLA TABLA para todos los deportistas 
    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS deportistas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            apellidos VARCHAR(100) NOT NULL,
            pais VARCHAR(50) NOT NULL,
            edad INT,
            genero VARCHAR(10),
            medallas_oro INT DEFAULT 0,
            medallas_plata INT DEFAULT 0,
            medallas_bronce INT DEFAULT 0,
            tipo_deporte VARCHAR(50) NOT NULL,
            -- Campos específicos (pueden ser NULL según el deporte)
            disciplina VARCHAR(50),
            tipo_esqui VARCHAR(50),
            especialidad VARCHAR(50),
            distancia_preferida INT,
            tipo_salto VARCHAR(50),
            altura_maxima DECIMAL(5,2)
        )";
        self::$conn->exec($sql);
        
        // Insertar datos de ejemplo si la tabla está vacía
        $result = self::$conn->query("SELECT COUNT(*) FROM deportistas");
        if ($result->fetchColumn() == 0) {
            $this->insertSampleData();
        }
    }
    
    private function insertSampleData() {
    $sql = "INSERT INTO deportistas 
            (nombre, apellidos, pais, edad, genero, medallas_oro, medallas_plata, medallas_bronce, tipo_deporte, 
             disciplina, tipo_esqui, especialidad, distancia_preferida, tipo_salto, altura_maxima) 
            VALUES 
            ('Marco', 'Schwarz', 'Austria', 28, 'M', 2, 1, 0, 'esqui', 'eslalon', 'libre', NULL, NULL, NULL, NULL),
            ('Mikaela', 'Shiffrin', 'USA', 29, 'F', 3, 0, 1, 'esqui', 'descenso', 'libre', NULL, NULL, NULL, NULL),
            ('Yuzuru', 'Hanyu', 'Japón', 29, 'M', 2, 2, 0, 'patinaje', NULL, NULL, 'figura', 25, NULL, NULL),
            ('Kamil', 'Stoch', 'Polonia', 36, 'M', 3, 0, 0, 'salto', NULL, NULL, NULL, NULL, 'trampolin', 140.5)";
    
    self::$conn->exec($sql);
}
}
?>