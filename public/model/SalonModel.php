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

        // Trae un salón por ID
        public function getSalon($id) {
            $stmt = $this->db->prepare(
                "SELECT * FROM salon WHERE id_salon = :id"
            );
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Crear salón
        public function addSalon($datos) {
            $stmt = $this->db->prepare(
                "INSERT INTO salon (edificio, salon, laboratorio)
                VALUES (:edificio, :salon, :laboratorio)"
            );
            $stmt->execute([
                ':edificio'    => $datos['edificio'],
                ':salon'       => $datos['salon'],
                ':laboratorio' => isset($datos['laboratorio']) ? 1 : 0
            ]);
            return $stmt->rowCount();
        }

        // Actualizar salón
        public function updateSalon($id, $datos) {
            $stmt = $this->db->prepare(
                "UPDATE salon SET 
                    edificio    = :edificio,
                    salon       = :salon,
                    laboratorio = :laboratorio
                WHERE id_salon = :id"
            );
            $stmt->execute([
                ':id'          => $id,
                ':edificio'    => $datos['edificio'],
                ':salon'       => $datos['salon'],
                ':laboratorio' => isset($datos['laboratorio']) ? 1 : 0
            ]);
            return $stmt->rowCount();
        }

        // Eliminar salón
        public function deleteSalon($id) {
            $stmt = $this->db->prepare(
                "DELETE FROM salon WHERE id_salon = :id"
            );
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount();
        }

        // Verificar si tiene exámenes asignados
        public function tieneExamenes($id) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM examen WHERE id_salon = :id"
            );
            $stmt->execute([':id' => $id]);
            return $stmt->fetchColumn() > 0;
        }       
    }