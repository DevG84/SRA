<?php
require_once __DIR__ . '/../../includes/db.php';

class PerfilModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    // Trae los datos del perfil según el rol
    public function getPerfil($id, $rol) {
        if ($rol === 'gestion') {
            $stmt = $this->db->prepare(
                "SELECT id_gestion AS id, nombre, apellido_p, apellido_m, correo
                 FROM gestion WHERE id_gestion = :id"
            );
        } else {
            $stmt = $this->db->prepare(
                "SELECT c.id_coordinador AS id, c.nombre, c.apellido_p, c.apellido_m,
                        c.correo, d.nombre AS departamento
                 FROM coordinador c
                 JOIN departamento d ON c.id_departamento = d.id_departamento
                 WHERE c.id_coordinador = :id"
            );
        }
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualiza nombre, apellidos y correo
    public function updateDatos($id, $rol, $datos) {
        $tabla   = $rol === 'gestion' ? 'gestion' : 'coordinador';
        $campoId = $rol === 'gestion' ? 'id_gestion' : 'id_coordinador';

        $stmt = $this->db->prepare(
            "UPDATE $tabla SET
                nombre     = :nombre,
                apellido_p = :apellido_p,
                apellido_m = :apellido_m,
                correo     = :correo
             WHERE $campoId = :id"
        );
        $stmt->execute([
            ':id'         => $id,
            ':nombre'     => $datos['nombre'],
            ':apellido_p' => $datos['apellido_p'],
            ':apellido_m' => $datos['apellido_m'] ?: null,
            ':correo'     => $datos['correo']
        ]);
        return $stmt->rowCount();
    }

    // Trae el hash actual de la contraseña (para verificar la actual)
    public function getContrasenaActual($id, $rol) {
        $tabla   = $rol === 'gestion' ? 'gestion' : 'coordinador';
        $campoId = $rol === 'gestion' ? 'id_gestion' : 'id_coordinador';

        $stmt = $this->db->prepare(
            "SELECT contrasena FROM $tabla WHERE $campoId = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }

    // Actualiza la contraseña
    public function updateContrasena($id, $rol, $nuevaContrasena) {
        $tabla   = $rol === 'gestion' ? 'gestion' : 'coordinador';
        $campoId = $rol === 'gestion' ? 'id_gestion' : 'id_coordinador';

        $hash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare(
            "UPDATE $tabla SET contrasena = :hash WHERE $campoId = :id"
        );
        $stmt->execute([':hash' => $hash, ':id' => $id]);
        return $stmt->rowCount();
    }

    // Verifica si el correo ya existe en otro usuario (de cualquiera de las dos tablas)
    public function correoExiste($correo, $rol, $excluirId) {
        if ($rol === 'gestion') {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM gestion WHERE correo = :correo AND id_gestion != :id"
            );
        } else {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM coordinador WHERE correo = :correo AND id_coordinador != :id"
            );
        }
        $stmt->execute([':correo' => $correo, ':id' => $excluirId]);
        return $stmt->fetchColumn() > 0;
    }
}