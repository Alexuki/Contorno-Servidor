<?php
// models/DeportistaRepository.php
// Versión reducida para examen - Solo filtro por país

class DeportistaRepository {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getConnection();
    }
    
    /**
     * Obtiene todos los deportistas, con filtro opcional por país
     */
    public function findAll($filtros = array()) {
        $sql = "SELECT * FROM deportistas WHERE 1=1";
        $params = array();
        
        // Filtro por país
        if (!empty($filtros['pais'])) {
            $sql .= " AND pais = ?";
            $params[] = $filtros['pais'];
        }
        
        $sql .= " ORDER BY nombre ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        $deportistas = array();
        while ($row = $stmt->fetch()) {
            $deportistas[] = $this->crearDesdeFila($row);
        }
        return $deportistas;
    }
    
    /**
     * Busca un deportista por su ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM deportistas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array($id));
        $row = $stmt->fetch();
        
        if ($row) {
            return $this->crearDesdeFila($row);
        }
        return null;
    }
    
    /**
     * Guarda un deportista (inserta si es nuevo, actualiza si existe)
     */
    public function save($deportista) {
        if ($deportista->getId()) {
            return $this->update($deportista);
        } else {
            return $this->insert($deportista);
        }
    }
    
    /**
     * Inserta un nuevo deportista en la base de datos
     */
    private function insert($deportista) {
        $sql = "INSERT INTO deportistas 
                (nombre, apellidos, pais, edad, genero, medallas_oro, medallas_plata, medallas_bronce, tipo_deporte,
                 disciplina, tipo_esqui, especialidad, distancia_preferida, tipo_salto, altura_maxima) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        
        // Preparar valores según el tipo de deporte
        if ($deportista->getTipoDeporte() == 'esqui') {
            $disciplina = $deportista->getDisciplina();
            $tipo_esqui = $deportista->getTipoEsqui();
            $especialidad = null;
            $distancia = null;
            $tipo_salto = null;
            $altura = null;
        } elseif ($deportista->getTipoDeporte() == 'patinaje') {
            $disciplina = null;
            $tipo_esqui = null;
            $especialidad = $deportista->getEspecialidad();
            $distancia = $deportista->getDistancia();
            $tipo_salto = null;
            $altura = null;
        } else { // salto
            $disciplina = null;
            $tipo_esqui = null;
            $especialidad = null;
            $distancia = null;
            $tipo_salto = $deportista->getTipoSalto();
            $altura = $deportista->getAlturaMaxima();
        }
        
        $stmt->execute(array(
            $deportista->getNombre(),
            $deportista->getApellidos(),
            $deportista->getPais(),
            $deportista->getEdad(),
            $deportista->getGenero(),
            $deportista->getMedallasOro(),
            $deportista->getMedallasPlata(),
            $deportista->getMedallasBronce(),
            $deportista->getTipoDeporte(),
            $disciplina,
            $tipo_esqui,
            $especialidad,
            $distancia,
            $tipo_salto,
            $altura
        ));
        
        return $this->conn->lastInsertId();
    }
    
    /**
     * Actualiza los datos de un deportista existente
     */
    private function update($deportista) {
        $sql = "UPDATE deportistas SET 
                nombre=?, apellidos=?, pais=?, edad=?, genero=?, 
                medallas_oro=?, medallas_plata=?, medallas_bronce=? 
                WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array(
            $deportista->getNombre(),
            $deportista->getApellidos(),
            $deportista->getPais(),
            $deportista->getEdad(),
            $deportista->getGenero(),
            $deportista->getMedallasOro(),
            $deportista->getMedallasPlata(),
            $deportista->getMedallasBronce(),
            $deportista->getId()
        ));
        return true;
    }
    
    /**
     * Elimina un deportista por su ID
     */
    public function delete($id) {
        $sql = "DELETE FROM deportistas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(array($id));
    }
    
    /**
     * Obtiene la lista de países para el filtro
     */
    public function getPaises() {
        $sql = "SELECT DISTINCT pais FROM deportistas ORDER BY pais";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
    * IMPLEMENTAR POR EL ALUMNO
    * Obtiene el medallero ordenado por puntuación
    * Cálculo: Oro=5pts, Plata=2pts, Bronce=1pto
    */
    public function getMedallero() {
        $sql = "SELECT pais, 
                SUM(medallas_oro) as total_oro,
                SUM(medallas_plata) as total_plata,
                SUM(medallas_bronce) as total_bronce,
                (SUM(medallas_oro)*5 + SUM(medallas_plata)*2 + SUM(medallas_bronce)*1) as puntuacion
                FROM deportistas 
                GROUP BY pais 
                ORDER BY puntuacion DESC, total_oro DESC, total_plata DESC";
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Crea un objeto Deportista desde una fila de la base de datos
     */
     private function crearDesdeFila($row) {
        if ($row['tipo_deporte'] == 'esqui') {
            return new Esquiador(
                $row['nombre'], $row['apellidos'], $row['pais'], $row['edad'], $row['genero'],
                $row['medallas_oro'], $row['medallas_plata'], $row['medallas_bronce'],
                $row['disciplina'], $row['tipo_esqui'], $row['id']
            );
        } elseif ($row['tipo_deporte'] == 'patinaje') {
            return new Patinador(
                $row['nombre'], $row['apellidos'], $row['pais'], $row['edad'], $row['genero'], 
                $row['medallas_oro'], $row['medallas_plata'], $row['medallas_bronce'],
                $row['especialidad'], $row['distancia_preferida'], $row['id']
            );
        } elseif ($row['tipo_deporte'] == 'salto') {
            return new Saltador(
                $row['nombre'], $row['apellidos'], $row['pais'], $row['edad'], $row['genero'],
                $row['medallas_oro'], $row['medallas_plata'], $row['medallas_bronce'],
                $row['tipo_salto'], $row['altura_maxima'], $row['id']
            );
        }
        return null;
    }

    public function __destruct() {
        Database::close();
    }
}
?>