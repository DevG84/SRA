<?php
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../model/EstadisticasModel.php';

try {
    $modelo = new EstadisticasModel();
    $stats  = $modelo->getEstadisticas();
    echo json_encode($stats);
} catch (Exception $e) {
    error_log("Error en EstadisticasController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([]);
}