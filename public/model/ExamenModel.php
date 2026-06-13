<?php
// Llamar conexion a BD
require_once __DIR__ . '/../../includes/db.php';
class ExamenModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    //JOINS necesarios

    public function getExamenes($carrera = '', $semestre = '', $materia = '', $texto = '', $id_coordinador = '') {
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
        if ($texto !== '') {
            $sql .= " AND (m.nombre LIKE :texto OR c.alias LIKE :texto OR co.nombre LIKE :texto OR co.apellido_p LIKE :texto OR co.apellido_m LIKE :texto)";
            $params[':texto'] = "%{$texto}%";
        }

        if ($id_coordinador !== '') {
            $sql .= " AND e.id_coordinador = :id_coordinador";
            $params[':id_coordinador'] = $id_coordinador;
        }
        

        $sql .= " ORDER BY m.nombre ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExamen($id) {

        // Obtenemos un examen por su ID
        $stmt = $this->db->prepare("SELECT * FROM examen WHERE id_examen = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addExamen($examen) {

        // Agregar un nuevo examen
        $stmt = $this->db->prepare(
            "INSERT INTO examen (id_materia, id_coordinador, id_salon, fecha, turno, horario, nota, guia, proyecto)
            VALUES (:id_materia, :id_coordinador, :id_salon, :fecha, :turno, :horario, :nota, :guia, :proyecto)"
        );

        $stmt->execute([
            ':id_materia' => $examen['id_materia'],
            ':id_coordinador' => $examen['id_coordinador'],
            ':id_salon' => $examen['id_salon'],
            ':fecha' => $examen['fecha'],
            ':turno' => $examen['turno'],
            ':horario' => $examen['horario'],
            ':nota' => $examen['nota'],
            ':guia' => $examen['guia'],
            ':proyecto' => $examen['proyecto']
        ]);
        return $stmt->rowCount();
    }

    public function updateExamen($id, $examen) {
        // Actualizar un examen existente
        $stmt = $this->db->prepare(
            "UPDATE examen SET 
                id_materia = :id_materia, 
                id_coordinador = :id_coordinador, 
                id_salon = :id_salon, 
                fecha = :fecha, 
                turno = :turno, 
                horario = :horario, 
                nota = :nota, 
                guia = :guia, 
                proyecto = :proyecto
            WHERE id_examen = :id"
        );

        $stmt->execute([
            ':id' => $id,
            ':id_materia' => $examen['id_materia'],
            ':id_coordinador' => $examen['id_coordinador'],
            ':id_salon' => $examen['id_salon'],
            ':fecha' => $examen['fecha'],
            ':turno' => $examen['turno'],
            ':horario' => $examen['horario'],
            ':nota' => $examen['nota'] ?: null,
            ':guia' => $examen['guia'] ?: null,
            ':proyecto' => $examen['proyecto'] ?: null
        ]);
        return $stmt->rowCount();
    }
    public function deleteExamen($id) {
        // Eliminar un examen por su ID
        $stmt = $this->db->prepare("DELETE FROM examen WHERE id_examen = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }
}