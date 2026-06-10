<?php

    //Pasamos BD
    require_once __DIR__ . '/../../includes/db.php';

    class LoginModel{
        private $db;

        public function __construct(){
            $this->db = (new Connection)->connect();
        }
     
        public function login($correo, $password) {
        $stmt = $this->db->prepare(
            "SELECT id_coordinador, nombre, apellido_p, contrasena 
             FROM coordinador WHERE correo = :correo"
        );
        $stmt->execute([':correo' => $correo]);
        $coordinador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($coordinador && password_verify($password, $coordinador['contrasena'])) {
            return $coordinador;
        }
        return null;
    }
    }