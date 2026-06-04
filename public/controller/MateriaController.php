<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json; charset=utf-8");

// Pasamos Model
require_once __DIR__ . '/../model/MateriaModel.php';

// Llamamos al modelo
try {
    $id_carrera = $_GET['carrera'] ?? ''; // Obtener el ID de carrera si se envió
    $modelo   = new MateriaModel();
    $materias = $modelo->getMaterias($id_carrera);
    echo json_encode($materias);
} catch (Exception $e) {
    error_log("Error en MateriaController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([]);
}