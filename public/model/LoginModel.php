<?php
require_once __DIR__ . '/../../includes/db.php';

class LoginModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    public function login($correo, $password) {
        // Buscar en coordinador
        $stmt = $this->db->prepare(
            "SELECT id_coordinador AS id, nombre, apellido_p, contrasena, 'coordinador' AS rol
             FROM coordinador WHERE correo = :correo"
        );
        $stmt->execute([':correo' => $correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no está, buscar en gestion
        if (!$usuario) {
            $stmt = $this->db->prepare(
                "SELECT id_gestion AS id, nombre, apellido_p, contrasena, 'gestion' AS rol
                 FROM gestion WHERE correo = :correo"
            );
            $stmt->execute([':correo' => $correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($usuario && password_verify($password, $usuario['contrasena'])) {
            return $usuario;
        }
        return null;
    }
}