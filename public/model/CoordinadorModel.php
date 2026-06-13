<?php
require_once __DIR__ . '/../../includes/db.php';

class CoordinadorModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    // Trae todos los coordinadores
    public function getCoordinadores() {
        $stmt = $this->db->prepare(
            "SELECT c.id_coordinador, c.nombre, c.apellido_p, c.apellido_m, 
                    c.correo, d.nombre AS departamento
             FROM coordinador c
             JOIN departamento d ON c.id_departamento = d.id_departamento
             ORDER BY c.apellido_p ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Trae un coordinador por ID
    public function getCoordinador($id) {
        $stmt = $this->db->prepare(
            "SELECT * FROM coordinador WHERE id_coordinador = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear coordinador
    public function addCoordinador($datos) {
        $stmt = $this->db->prepare(
            "INSERT INTO coordinador (nombre, apellido_p, apellido_m, id_departamento, correo, contrasena)
             VALUES (:nombre, :apellido_p, :apellido_m, :id_departamento, :correo, :contrasena)"
        );
        $stmt->execute([
            ':nombre'          => $datos['nombre'],
            ':apellido_p'      => $datos['apellido_p'],
            ':apellido_m'      => $datos['apellido_m'] ?: null,
            ':id_departamento' => $datos['id_departamento'],
            ':correo'          => $datos['correo'],
            ':contrasena'      => password_hash($datos['contrasena'], PASSWORD_BCRYPT)
        ]);
        return $stmt->rowCount();
    }

    // Actualizar coordinador
    public function updateCoordinador($id, $datos) {
        // Si viene nueva contraseña la hashea, si no conserva la actual
        $sql = "UPDATE coordinador SET 
                    nombre = :nombre,
                    apellido_p = :apellido_p,
                    apellido_m = :apellido_m,
                    id_departamento = :id_departamento,
                    correo = :correo";

        $params = [
            ':id'              => $id,
            ':nombre'          => $datos['nombre'],
            ':apellido_p'      => $datos['apellido_p'],
            ':apellido_m'      => $datos['apellido_m'] ?: null,
            ':id_departamento' => $datos['id_departamento'],
            ':correo'          => $datos['correo']
        ];

        if (!empty($datos['contrasena'])) {
            $sql .= ", contrasena = :contrasena";
            $params[':contrasena'] = password_hash($datos['contrasena'], PASSWORD_BCRYPT);
        }

        $sql .= " WHERE id_coordinador = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    // Eliminar coordinador
    public function deleteCoordinador($id) {
        $stmt = $this->db->prepare(
            "DELETE FROM coordinador WHERE id_coordinador = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    public function tieneExamenes($id) {
    $stmt = $this->db->prepare(
        "SELECT COUNT(*) FROM examen WHERE id_coordinador = :id"
    );
    $stmt->execute([':id' => $id]);
    return $stmt->fetchColumn() > 0;
}
}