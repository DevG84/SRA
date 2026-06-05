<?php

    // CArgamos BD
    require_once __DIR__ . '/../../includes/db.php';

    class SalonModel {
        private $db;

        public function __construct() {
            $this->db = (new Connection()) -> connect();
        }

        public function getSalones() {
            try {
                $stmt = $this->db->prepare(
                            "SELECT id_salon, edificio, salon, laboratorio 
                            FROM salon 
                            ORDER BY edificio ASC, salon ASC"
                        );                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error al obtener salones: " . $e->getMessage());
                throw new Exception("Error al obtener los salones");
            }
        }
    }