<?php
require_once __DIR__ . '/../../includes/db.php';

class EstadisticasModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    public function getEstadisticas() {
        $stats = [];

        // Total de exámenes
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM examen");
        $stmt->execute();
        $stats['examenes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total de coordinadores
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM coordinador");
        $stmt->execute();
        $stats['coordinadores'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total de carreras
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM carrera");
        $stmt->execute();
        $stats['carreras'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total de salones
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM salon");
        $stmt->execute();
        $stats['salones'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        return $stats;
    }
}