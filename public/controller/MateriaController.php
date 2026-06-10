<?php
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../model/MateriaModel.php';

try {
    $modelo      = new MateriaModel(); // Instanciar Modelo
    $id_materia  = $_GET['id_materia'] ?? '';

    // Obtener Materia por ID si se proporciona
    if ($id_materia !== '') {
        echo json_encode($modelo->getMateria($id_materia));
        exit;
    }

    // Obtener materias filtradas por carrera y semestre        
    $id_carrera  = $_GET['carrera']  ?? '';
    $id_semestre = $_GET['semestre'] ?? '';
    $materias    = $modelo->getMaterias($id_carrera, $id_semestre);
    echo json_encode($materias);

} catch (Exception $e) {
    error_log("Error en MateriaController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([]);
}