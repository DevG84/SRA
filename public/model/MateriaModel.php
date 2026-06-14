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
        $params = [];

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

    // Crear materia
    public function addMateria($datos) {
        $stmt = $this->db->prepare(
            "INSERT INTO materia (nombre, id_departamento, id_carrera, semestre)
            VALUES (:nombre, :id_departamento, :id_carrera, :semestre)"
        );
        $stmt->execute([
            ':nombre'          => $datos['nombre'],
            ':id_departamento' => $datos['id_departamento'],
            ':id_carrera'      => $datos['id_carrera'],
            ':semestre'        => $datos['semestre']
        ]);
        return $stmt->rowCount();
    }

    // Actualizar materia
    public function updateMateria($id, $datos) {
        $stmt = $this->db->prepare(
            "UPDATE materia SET
                nombre          = :nombre,
                id_departamento = :id_departamento,
                id_carrera      = :id_carrera,
                semestre        = :semestre
            WHERE id_materia = :id"
        );
        $stmt->execute([
            ':id'              => $id,
            ':nombre'          => $datos['nombre'],
            ':id_departamento' => $datos['id_departamento'],
            ':id_carrera'      => $datos['id_carrera'],
            ':semestre'        => $datos['semestre']
        ]);
        return $stmt->rowCount();
    }

    // Eliminar materia
    public function deleteMateria($id) {
        $stmt = $this->db->prepare(
            "DELETE FROM materia WHERE id_materia = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // Verificar si tiene exámenes asignados
    public function tieneExamenes($id) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM examen WHERE id_materia = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    // Trae todas las materias con joins para la vista de gestión
    public function getMateriasCompleto() {
        $stmt = $this->db->prepare(
            "SELECT m.id_materia, m.nombre, m.semestre,
                    c.alias AS carrera,
                    d.nombre AS departamento
            FROM materia m
            JOIN carrera c ON m.id_carrera = c.id_carrera
            JOIN departamento d ON m.id_departamento = d.id_departamento
            ORDER BY c.alias ASC, m.semestre ASC, m.nombre ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}