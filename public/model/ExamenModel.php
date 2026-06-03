<?php
// Llamar conexion a BD
require_once __DIR__ . '/../../includes/db.php';
class ExamenModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    //JOINS necesarios

    public function getExamenes($carrera = '', $semestre = '', $materia = '', $coord = '') {
        $sql = "SELECT 
                    e.id_examen,
                    m.nombre            AS materia,
                    c.alias             AS carrera,
                    co.nombre           AS coordNombre,
                    co.apellido_p       AS coordApellidoP,
                    co.apellido_m       AS coordApellidoM,
                    co.correo           AS coordCorreo,
                    s.edificio,
                    s.salon,
                    s.laboratorio,
                    e.fecha,
                    e.turno,
                    e.horario,
                    e.nota,
                    e.guia,
                    e.proyecto
                FROM examen e
                JOIN materia m       ON e.id_materia     = m.id_materia
                JOIN carrera c       ON m.id_carrera      = c.id_carrera
                JOIN coordinador co  ON e.id_coordinador  = co.id_coordinador
                JOIN salon s         ON e.id_salon        = s.id_salon
                WHERE 1=1";

        $params = [];

        // Opciones de filtro

        if ($carrera !== '') {
            $sql .= " AND c.id_carrera = :carrera";
            $params[':carrera'] = $carrera;
        }
        if ($semestre !== '') {
            $sql .= " AND m.semestre = :semestre";
            $params[':semestre'] = $semestre;
        }
        if ($materia !== '') {
            $sql .= " AND m.id_materia = :materia";
            $params[':materia'] = $materia;
        }
        if ($coord !== '') {
            $sql .= " AND e.id_coordinador = :coord";
            $params[':coord'] = $coord;
        }
        

        $sql .= " ORDER BY m.nombre ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExamen($id) {}
    public function addExamen($examen) {}
    public function updateExamen($id, $examen) {}
    public function deleteExamen($id) {}
}