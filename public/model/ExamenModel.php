<?php
class ExamenModel {
    private $db;

    public function __construct() {
        $this->db = (new Connection)->connect();
    }

    public function getExamenes() {}
    public function getExamen($id) {}
    public function addExamen($examen) {}
    public function updateExamen($id, $examen) {}
    public function deleteExamen($id) {}
}