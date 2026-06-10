<?php

// Llamamos a la conexión a la base de datos

require_once __DIR__ . '/../../includes/db.php';

class MateriaModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    // Trae todas las materias ordenadas alfabéticamente
    public function getMaterias($id_carrera = '', $id_semestre = '') {

        $sql = "SELECT id_materia, nombre FROM materia WhERE 1=1";

        if($id_carrera !== '') {
            $sql .= " AND id_carrera = :carrera";
            $params[':carrera'] = $id_carrera;
        }

        if($id_semestre !== '') {
            $sql .= " AND semestre = :semestre";
            $params[':semestre'] = $id_semestre;
        }

        $sql .= " ORDER BY nombre ASC";

            error_log("SQL: " . $sql . " | params: " . json_encode($params));

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Trae una materia por su ID
    public function getMateria($id) {
        $stmt = $this->db->prepare(
            "SELECT id_materia, id_carrera, semestre FROM materia WHERE id_materia = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}