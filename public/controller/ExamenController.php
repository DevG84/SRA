<?php

    // Pasamos Model
    require_once __DIR__ . '/../model/ExamenModel.php';

    // Obtener Parametros
    $carrera = $_GET['carrera'] ?? '';
    $semestre = $_GET['semestre'] ?? '';
    $materia = $_GET['materia'] ?? '';
    $coord = $_GET['coord'] ?? '';

    //Limpiamos datos
    $carrera = trim($carrera);
    $semestre = trim($semestre);
    $materia = trim($materia);
    $coord = trim($coord);

    // Llamamos al modelo
    try {
        $model = new ExamenModel();
        $examenes = $model -> getExamenes($carrera, $semestre, $materia, $coord);
        echo json_encode($examenes);
    } catch (Exception $e) {
        error_log("Error en ExamenController: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([ "msj" => "Error al obtener los exámenes" ]);
    }
