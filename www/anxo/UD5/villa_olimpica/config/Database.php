<?php
// config/Database.php

class Database {
    private static $instance = null;
    private static $conn = null;
    
    // Configuración como atributos estáticos (como tus variables)
    private static $servername = "db";
    private static $username = "root";
    private static $password = "test";
    private static $dbname = "vila_olimpica";
    
    /**
     * Constructor privado 
     * Crea la conexión y la base de datos si no existe
     */
    private function __construct() {
        try {
            // 1. Crear conexión inicial (sin especificar base de datos)
            self::$conn = new PDO("mysql:host=" . self::$servername, self::$username, self::$password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // 2. Crear la base de datos si no existe
            $sql = "CREATE DATABASE IF NOT EXISTS " . self::$dbname;
            self::$conn->exec($sql);
            
            // 3. Reconectar indicando la base de datos específica
            self::$conn = new PDO("mysql:host=" . self::$servername . ";dbname=" . self::$dbname, self::$username, self::$password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // 4. Crear las tablas necesarias
            $this->createTables();
            
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage() . "<br>";
        }
    }
    
    /**
     * Obtiene la conexión 
     * Si no existe la instancia, la crea
     */
    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$conn;
    }
    
    /**
     * Cierra la conexión 
     */
    public static function close() {
        self::$conn = null;
        self::$instance = null;
    }
    
    /**
     * Crea las tablas necesarias
     */
    private function createTables() {
        // Tabla principal de deportistas
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
            fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        self::$conn->exec($sql);
        
        // Tabla específica para Esquiadores
        $sql = "CREATE TABLE IF NOT EXISTS esquiadores (
            deportista_id INT PRIMARY KEY,
            disciplina VARCHAR(50),
            tipo_esqui VARCHAR(50),
            FOREIGN KEY (deportista_id) REFERENCES deportistas(id) ON DELETE CASCADE
        )";
        self::$conn->exec($sql);
        
        // Tabla específica para Patinadores
        $sql = "CREATE TABLE IF NOT EXISTS patinadores (
            deportista_id INT PRIMARY KEY,
            especialidad VARCHAR(50),
            distancia_preferida INT,
            FOREIGN KEY (deportista_id) REFERENCES deportistas(id) ON DELETE CASCADE
        )";
        self::$conn->exec($sql);
        
        // Tabla específica para Saltadores
        $sql = "CREATE TABLE IF NOT EXISTS saltadores (
            deportista_id INT PRIMARY KEY,
            tipo_salto VARCHAR(50),
            altura_maxima DECIMAL(5,2),
            FOREIGN KEY (deportista_id) REFERENCES deportistas(id) ON DELETE CASCADE
        )";
        self::$conn->exec($sql);
        
        // Insertar datos de ejemplo si la tabla está vacía
        $result = self::$conn->query("SELECT COUNT(*) FROM deportistas");
        if ($result->fetchColumn() == 0) {
            $this->insertSampleData();
        }
    }
    
    /**
     * Inserta datos de ejemplo
     */
    private function insertSampleData() {
        try {
            // Deportistas de ejemplo
            $deportistas = [
                ['Marco', 'Schwarz', 'Austria', 28, 'M', 2, 1, 0, 'esqui'],
                ['Mikaela', 'Shiffrin', 'USA', 29, 'F', 3, 0, 1, 'esqui'],
                ['Yuzuru', 'Hanyu', 'Japón', 29, 'M', 2, 2, 0, 'patinaje'],
                ['Jutta', 'Leerdman', 'Países Bajos', 37, 'F', 5, 1, 1, 'patinaje'],
                ['Kamil', 'Stoch', 'Polonia', 36, 'M', 3, 0, 0, 'salto'],
            ];
            
            $stmt = self::$conn->prepare("INSERT INTO deportistas 
                (nombre, apellidos, pais, edad, genero, medallas_oro, medallas_plata, medallas_bronce, tipo_deporte) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            foreach ($deportistas as $d) {
                $stmt->execute($d);
                $id = self::$conn->lastInsertId();
                
                // Insertar en tabla específica según tipo
                if ($d[8] == 'esqui') {
                    $sql = "INSERT INTO esquiadores (deportista_id, disciplina, tipo_esqui) 
                            VALUES (?, 'eslalon', 'libre')";
                    self::$conn->prepare($sql)->execute([$id]);
                } elseif ($d[8] == 'patinaje') {
                    $sql = "INSERT INTO patinadores (deportista_id, especialidad, distancia_preferida) 
                            VALUES (?, 'figura', 1500)";
                    self::$conn->prepare($sql)->execute([$id]);
                } elseif ($d[8] == 'salto') {
                    $sql = "INSERT INTO saltadores (deportista_id, tipo_salto, altura_maxima) 
                            VALUES (?, 'trampolín', 140.5)";
                    self::$conn->prepare($sql)->execute([$id]);
                }
            }
        } catch(PDOException $e) {
            echo "Error insertando datos: " . $e->getMessage();
        }
    }
}
?>