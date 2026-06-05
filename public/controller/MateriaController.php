<?php

// Pasamos Model
require_once __DIR__ . '/../model/MateriaModel.php';

// Llamamos al modelo
try {
    $id_carrera = $_GET['carrera'] ?? ''; 
    $id_semestre = $_GET['semestre'] ?? '';
    $modelo   = new MateriaModel();
    $materias = $modelo->getMaterias($id_carrera, $id_semestre);
    echo json_encode($materias);
} catch (Exception $e) {
    error_log("Error en MateriaController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([]);
}