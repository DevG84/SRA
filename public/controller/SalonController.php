<?php
    require_once __DIR__ . '/../model/SalonModel.php';

    try {
        $modelo   = new SalonModel();
        $salones  = $modelo->getSalones();
        echo json_encode($salones);
    } catch (Exception $e) {
        error_log("Error en SalonController: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([]);
    }