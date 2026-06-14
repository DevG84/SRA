<?php
require_once __DIR__ . '/../../includes/db.php';

class InscripcionModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    // Exámenes en los que el alumno YA está registrado
    public function getExamenesInscritos($id_alumno) {
        $stmt = $this->db->prepare(
            "SELECT e.id_examen, m.nombre AS materia, c.alias AS carrera,
                    e.fecha, e.turno, e.horario, s.edificio, s.salon
             FROM alumno_examen ae
             JOIN examen e  ON ae.id_examen = e.id_examen
             JOIN materia m ON e.id_materia = m.id_materia
             JOIN carrera c ON m.id_carrera = c.id_carrera
             JOIN salon s   ON e.id_salon = s.id_salon
             WHERE ae.id_alumno = :id_alumno
             ORDER BY e.fecha ASC, e.horario ASC"
        );
        $stmt->execute([':id_alumno' => $id_alumno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Exámenes en los que el alumno NO está registrado (disponibles)
    public function getExamenesDisponibles($id_alumno) {
        $stmt = $this->db->prepare(
            "SELECT e.id_examen, m.nombre AS materia, c.alias AS carrera,
                    e.fecha, e.turno, e.horario, s.edificio, s.salon
             FROM examen e
             JOIN materia m ON e.id_materia = m.id_materia
             JOIN carrera c ON m.id_carrera = c.id_carrera
             JOIN salon s   ON e.id_salon = s.id_salon
             WHERE e.id_examen NOT IN (
                 SELECT id_examen FROM alumno_examen WHERE id_alumno = :id_alumno
             )
             ORDER BY e.fecha ASC, e.horario ASC"
        );
        $stmt->execute([':id_alumno' => $id_alumno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Inscribir alumno a un examen
    public function inscribir($id_alumno, $id_examen) {
        $stmt = $this->db->prepare(
            "INSERT INTO alumno_examen (id_alumno, id_examen) VALUES (:id_alumno, :id_examen)"
        );
        $stmt->execute([
            ':id_alumno' => $id_alumno,
            ':id_examen' => $id_examen
        ]);
        return $stmt->rowCount();
    }

    // Quitar inscripción
    public function desinscribir($id_alumno, $id_examen) {
        $stmt = $this->db->prepare(
            "DELETE FROM alumno_examen WHERE id_alumno = :id_alumno AND id_examen = :id_examen"
        );
        $stmt->execute([
            ':id_alumno' => $id_alumno,
            ':id_examen' => $id_examen
        ]);
        return $stmt->rowCount();
    }
}