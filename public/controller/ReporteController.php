<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../model/ReporteModel.php';

// Requiere sesión activa
if (!isset($_SESSION['id_coordinador'])) {
    http_response_code(401);
    echo json_encode(['cod' => 0, 'msj' => 'No autorizado', 'icono' => 'error']);
    exit;
}

$modelo = new ReporteModel();

try {
    $accion = $_GET['accion'] ?? 'examenes';

    if ($accion === 'alumnos') {
        // Traer alumnos inscritos a un examen
        $id_examen = $_GET['id_examen'] ?? '';
        if (empty($id_examen)) {
            throw new Exception("ID de examen no proporcionado");
        }
        $alumnos = $modelo->getAlumnosPorExamen($id_examen);
        echo json_encode($alumnos);

    } else {
        // Traer exámenes — todos si es gestión, solo propios si es coordinador
        $rol = $_SESSION['rol'] ?? 'coordinador';

        if ($rol === 'gestion') {
            $examenes = $modelo->getExamenesReporte();
        } else {
            $examenes = $modelo->getExamenesReporte($_SESSION['id_coordinador']);
        }

        echo json_encode($examenes);
    }

} catch (Exception $e) {
    error_log("Error en ReporteController: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([]);
}