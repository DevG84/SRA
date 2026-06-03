<?php

// Llamamos a la conexión a la base de datos

require_once __DIR__ . '/../../includes/db.php';

class MateriaModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    // Trae todas las materias ordenadas alfabéticamente
    public function getMaterias($id_carrera = '') {

        $sql = "SELECT id_materia, nombre FROM materia";
        $params = [];

        if($id_carrera !== '') {
            $sql .= " WHERE id_carrera = :carrera";
            $params[':carrera'] = $id_carrera;
        }

        $sql .= " ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}