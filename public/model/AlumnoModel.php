<?php
require_once __DIR__ . '/../../includes/db.php';

class AlumnoModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    // Trae todos los alumnos
    public function getAlumnos() {
        $stmt = $this->db->prepare(
            "SELECT id_alumno, boleta, nombre, apellido_p, apellido_m
             FROM alumno
             ORDER BY apellido_p ASC, nombre ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Trae un alumno por ID
    public function getAlumno($id) {
        $stmt = $this->db->prepare(
            "SELECT * FROM alumno WHERE id_alumno = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear alumno
    public function addAlumno($datos) {
        $stmt = $this->db->prepare(
            "INSERT INTO alumno (boleta, nombre, apellido_p, apellido_m)
             VALUES (:boleta, :nombre, :apellido_p, :apellido_m)"
        );
        $stmt->execute([
            ':boleta'     => $datos['boleta'],
            ':nombre'     => $datos['nombre'],
            ':apellido_p' => $datos['apellido_p'],
            ':apellido_m' => $datos['apellido_m'] ?: null
        ]);
        return $stmt->rowCount();
    }

    // Actualizar alumno
    public function updateAlumno($id, $datos) {
        $stmt = $this->db->prepare(
            "UPDATE alumno SET
                boleta     = :boleta,
                nombre     = :nombre,
                apellido_p = :apellido_p,
                apellido_m = :apellido_m
             WHERE id_alumno = :id"
        );
        $stmt->execute([
            ':id'         => $id,
            ':boleta'     => $datos['boleta'],
            ':nombre'     => $datos['nombre'],
            ':apellido_p' => $datos['apellido_p'],
            ':apellido_m' => $datos['apellido_m'] ?: null
        ]);
        return $stmt->rowCount();
    }

    // Eliminar alumno
    public function deleteAlumno($id) {
        $stmt = $this->db->prepare(
            "DELETE FROM alumno WHERE id_alumno = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // Verificar si la boleta ya existe (para evitar duplicados)
    public function boletaExiste($boleta, $excluirId = null) {
        $sql = "SELECT COUNT(*) FROM alumno WHERE boleta = :boleta";
        $params = [':boleta' => $boleta];

        if ($excluirId !== null) {
            $sql .= " AND id_alumno != :id";
            $params[':id'] = $excluirId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    // Verificar si tiene exámenes inscritos
    public function tieneExamenes($id) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM alumno_examen WHERE id_alumno = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }
}