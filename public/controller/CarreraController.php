<?php

// Pasamos Model
require_once __DIR__ . '/../model/CarreraModel.php';

// Llamamos al modelo
try {
    $modelo   = new CarreraModel();
    $carreras = $modelo->getCarreras();
    echo json_encode($carreras);
} catch (Exception $e) {
    error_log("Error en CarreraController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([]);
}