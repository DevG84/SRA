<?php

// Llamamos a la conexión a la base de datos

require_once __DIR__ . '/../../includes/db.php';

class CarreraModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    // Trae todas las carreras ordenadas alfabéticamente
    public function getCarreras() {
        $stmt = $this->db->prepare("SELECT id_carrera, nombre, alias FROM carrera ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}