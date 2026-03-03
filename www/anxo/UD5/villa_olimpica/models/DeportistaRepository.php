<?php
// models/DeportistaRepository.php

/**
 * Clase DeportistaRepository
 * Se encarga de toda la comunicación con la base de datos (CRUD).
 */

class DeportistaRepository {
    // Guarda la conexión a la base de datos (objeto PDO)
    private $conn;
    
    /**
     * Constructor
     * Se ejecuta automáticamente al crear un objeto DeportistaRepository.
     * Obtiene la conexión a la base de datos usando la clase Database.
     */
    public function __construct() {
        $this->conn = Database::getConnection();
    }
    
    /**
     * findAll
     * Busca todos los deportistas en la base de datos.
     * Acepta un array de filtros opcionales (tipo de deporte, país, mínimo de medallas).
     * Devuelve un array de objetos Deportista (Esquiador, Patinador o Saltador).
     */
    public function findAll($filtros = array()) {
        // Consulta SQL que une la tabla principal con las tablas específicas de cada deporte
        // LEFT JOIN permite traer los datos aunque no existan registros en las tablas hijas
        $sql = "SELECT d.*, e.disciplina, e.tipo_esqui, p.especialidad, p.distancia_preferida, s.tipo_salto, s.altura_maxima
                FROM deportistas d
                LEFT JOIN esquiadores e ON d.id = e.deportista_id
                LEFT JOIN patinadores p ON d.id = p.deportista_id
                LEFT JOIN saltadores s ON d.id = s.deportista_id
                WHERE 1=1";
        $params = array();
        
        // Si se especifica algún tipo de filtro, añade la condición a la sentencia SQL
        if (!empty($filtros['tipo'])) {
            $sql .= " AND d.tipo_deporte = ?";
            $params[] = $filtros['tipo'];
        }
        
        if (!empty($filtros['pais'])) {
            $sql .= " AND d.pais = ?";
            $params[] = $filtros['pais'];
        }
        
        if (!empty($filtros['min_medallas'])) {
            $sql .= " AND (d.medallas_oro + d.medallas_plata + d.medallas_bronce) >= ?";
            $params[] = $filtros['min_medallas'];
        }
        
        $sql .= " ORDER BY d.medallas_oro DESC";
        
        // Prepara y ejecuta la consulta con los parámetros
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        // Crea un array vacío y va añadiendo objetos Deportista creados desde cada fila de la BD
        $deportistas = array();
        while ($row = $stmt->fetch()) {
            $deportistas[] = $this->crearDesdeFila($row);
        }
        return $deportistas;
    }

    /**
     * findById
     * Busca un deportista específico por su ID.
     * Devuelve un objeto Deportista si lo encuentra, o null si no existe.
     */
    public function findById($id) {
        $sql = "SELECT d.*, e.disciplina, e.tipo_esqui, p.especialidad, p.distancia_preferida, s.tipo_salto, s.altura_maxima
                FROM deportistas d
                LEFT JOIN esquiadores e ON d.id = e.deportista_id
                LEFT JOIN patinadores p ON d.id = p.deportista_id
                LEFT JOIN saltadores s ON d.id = s.deportista_id
                WHERE d.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array($id));
        $row = $stmt->fetch();
        
        if ($row) {
            return $this->crearDesdeFila($row);
        }
        return null;
    }
    
    /**
     * save
     * Guarda un deportista en la base de datos.
     * Decide si debe insertar (nuevo) o actualizar (existente) según si tiene ID.
     */
    public function save($deportista) {
        if ($deportista->getId()) {
            return $this->update($deportista);
        } else {
            return $this->insert($deportista);
        }
    }
    
    /**
     * insert
     * Inserta un nuevo deportista en la base de datos.
     * Primero inserta en la tabla 'deportistas', obtiene el ID generado automáticamente,
     * y luego inserta los datos específicos en la tabla correspondiente (esquiadores, patinadores o saltadores).
     * Devuelve el ID del nuevo registro.
     */
    private function insert($deportista) {
        $sql = "INSERT INTO deportistas (nombre, apellidos, pais, edad, genero, medallas_oro, medallas_plata, medallas_bronce, tipo_deporte) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            $deportista->getTipoDeporte()
        ));
        
        $id = $this->conn->lastInsertId();
        
        // Según el tipo de deporte, inserta los datos específicos en la tabla correspondiente
        if ($deportista->getTipoDeporte() == 'esqui') {
            $sql = "INSERT INTO esquiadores (deportista_id, disciplina, tipo_esqui) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $detalles = $deportista->getDetalles();
            $stmt->execute(array($id, $detalles['disciplina'], $detalles['tipo_esqui']));
        } elseif ($deportista->getTipoDeporte() == 'patinaje') {
            $sql = "INSERT INTO patinadores (deportista_id, especialidad, distancia_preferida) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $detalles = $deportista->getDetalles();
            $stmt->execute(array($id, $detalles['especialidad'], $detalles['distancia']));
        } elseif ($deportista->getTipoDeporte() == 'salto') {
            $sql = "INSERT INTO saltadores (deportista_id, tipo_salto, altura_maxima) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $detalles = $deportista->getDetalles();
            $stmt->execute(array($id, $detalles['tipo_salto'], $detalles['altura_maxima']));
        }
        
        return $id;
    }
    
    /**
     * update
     * Actualiza los datos de un deportista existente en la base de datos.
     * Modifica la tabla 'deportistas' usando el ID para identificar el registro.
     * (No está implementado, podéis probar vosotros a implementarlo en casa)
     */
    private function update($deportista) {
        $sql = "UPDATE deportistas SET nombre=?, apellidos=?, pais=?, edad=?, genero=?, 
                medallas_oro=?, medallas_plata=?, medallas_bronce=? WHERE id=?";
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
     * delete
     * Elimina un deportista de la base de datos por su ID.
     */
    public function delete($id) {
        $sql = "DELETE FROM deportistas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(array($id));
    }
    
    /**
     * getPaises
     * Obtiene una lista de todos los países distintos que hay en la base de datos.
     */
    public function getPaises() {
        $sql = "SELECT DISTINCT pais FROM deportistas ORDER BY pais";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * crearDesdeFila
     * Método privado que crea un objeto Deportista (Esquiador, Patinador o Saltador)
     * a partir de una fila de la base de datos.
     * Detecta el tipo de deporte y crea la instancia de la clase correspondiente.
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

    /**
     * Destructor: se ejecuta automáticamente cuando el objeto se destruye
     * Cierra la conexión cuando ya no se necesita el Repository
     */
    public function __destruct() {
        Database::close();
    }
}
?>