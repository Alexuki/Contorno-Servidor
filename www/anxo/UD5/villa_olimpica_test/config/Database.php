<?php
    class Database {

        private static $instance = null;
        private static $conn = null;

        const SERVER = "db";
        const USER = "root";
        const PASS = "test";
        const DB = "villa_olimpica";

        private function __construct() {

            try {
                $conexion = new PDO("mysql:host=" . self::SERVER, self::USER, self::PASS);
                $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn = $conexion;

                $sql = "CREATE DATABASE IF NOT EXISTS " . self::DB;
                self::$conn->exec($sql);
                self::$conn->exec("USE " . self::DB);

                $this->createTables();

            } catch (PDOException $e) {
                die("Error de conexión a BBDD: " . $e->getMessage() . "<br>");
            }
        }


        public static function getConnection() {
            if(self::$instance == null) {
                self::$instance = new Database();
            }
            return self::$conn;
        }

        public static function closeConnection() {
            self::$conn = null;
            self::$instance = null;
        }

        private function createTables(){
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

            $sql = "CREATE TABLE IF NOT EXISTS esquiadores (
                deportista_id INT PRIMARY KEY,
                disciplina VARCHAR(50),
                tipo_esqui VARCHAR(50),
                FOREIGN KEY (deportista_id) REFERENCES deportistas(id) ON DELETE CASCADE
            )";
            self::$conn->exec($sql);

             $sql = "CREATE TABLE IF NOT EXISTS patinadores (
                deportista_id INT PRIMARY KEY,
                especialidad VARCHAR(50),
                distancia_preferida INT,
                FOREIGN KEY (deportista_id) REFERENCES deportistas(id) ON DELETE CASCADE
            )";
            self::$conn->exec($sql);

             $sql = "CREATE TABLE IF NOT EXISTS saltadores (
                deportista_id INT PRIMARY KEY,
                tipo_salto VARCHAR(50),
                altura_maxima DECIMAL(5,2),
                FOREIGN KEY (deportista_id) REFERENCES deportistas(id) ON DELETE CASCADE
            )";
            self::$conn->exec($sql);

            $result = self::$conn->query("SELECT COUNT(*) FROM deportistas");
            if ($result->fetchColumn() == 0) {
                $this->insertSampleData();
            }

        }

        private function insertSampleData() {
        try {

            $deportistas = [
                ['Marco', 'Schwarz', 'Austria', 28, 'M', 2, 1, 0, 'esqui'],
                ['Mikaela', 'Shiffrin', 'USA', 29, 'F', 3, 0, 1, 'esqui'],
                ['Yuzuru', 'Hanyu', 'Japón', 29, 'M', 2, 2, 0, 'patinaje'],
                ['Jutta', 'Leerdman', 'Países Bajos', 37, 'F', 5, 1, 1, 'patinaje'],
                ['Kamil', 'Stoch', 'Polonia', 36, 'M', 3, 0, 0, 'salto'],
            ];

            $sql = "INSERT INTO deportistas
                (nombre, apellidos, pais, edad, genero, medallas_oro, medallas_plata, medallas_bronce, tipo_deporte)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = self::$conn->prepare($sql);

            foreach ($deportistas as $d) {
                $stmt->execute($d);
                $id = self::$conn->lastInsertId();
                $tipo = $d[8];

                switch ($tipo) {
                    case "esqui": {
                        $sql = "INSERT INTO esquiadores (deportista_id, disciplina, tipo_esqui) 
                        VALUES (?, 'eslalon', 'libre')";
                        self::$conn->prepare($sql)->execute([$id]);
                        break;
                    }
                    case "patinaje": {
                        $sql = "INSERT INTO patinadores (deportista_id, especialidad, distancia_preferida) 
                        VALUES (?, 'figura', 1500)";
                        self::$conn->prepare($sql)->execute([$id]);
                        break;
                    }
                    case "salto": {
                        $sql = "INSERT INTO saltadores (deportista_id, tipo_salto, altura_maxima) 
                        VALUES (?, 'trampolín', 140.5)";
                        self::$conn->prepare($sql)->execute([$id]);
                        break;
                    }
                }
            }
            
        } catch (PDOException $e) {
            echo "Error al insertar los datos de ejemplo: " . $e->getMessage();
        }
    }

    }
?>