<?php
require_once __DIR__ . '/../../includes/db.php';

class ReporteModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    // Trae exámenes para el reporte — todos para gestión o solo los del coordinador
    public function getExamenesReporte($id_coordinador = null) {
        $sql = "SELECT e.id_examen, m.nombre AS materia, c.alias AS carrera,
                       e.fecha, e.turno, e.horario,
                       s.edificio, s.salon,
                       co.nombre AS coordNombre, co.apellido_p AS coordApellidoP,
                       (SELECT COUNT(*) FROM alumno_examen ae WHERE ae.id_examen = e.id_examen) AS total_alumnos
                FROM examen e
                JOIN materia m   ON e.id_materia = m.id_materia
                JOIN carrera c   ON m.id_carrera = c.id_carrera
                JOIN salon s     ON e.id_salon = s.id_salon
                JOIN coordinador co ON e.id_coordinador = co.id_coordinador";

        $params = [];

        if ($id_coordinador !== null) {
            $sql .= " WHERE e.id_coordinador = :id_coordinador";
            $params[':id_coordinador'] = $id_coordinador;
        }

        $sql .= " ORDER BY e.fecha DESC, e.horario ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Trae los alumnos inscritos a un examen específico
    public function getAlumnosPorExamen($id_examen) {
        $stmt = $this->db->prepare(
            "SELECT a.id_alumno, a.boleta, a.nombre, a.apellido_p, a.apellido_m, ae.fecha_registro
             FROM alumno_examen ae
             JOIN alumno a ON ae.id_alumno = a.id_alumno
             WHERE ae.id_examen = :id_examen
             ORDER BY a.apellido_p ASC, a.nombre ASC"
        );
        $stmt->execute([':id_examen' => $id_examen]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}