<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../model/InscripcionModel.php';

// Solo gestión puede usar este controller
if (!isset($_SESSION['id_coordinador']) || ($_SESSION['rol'] ?? '') !== 'gestion') {
    http_response_code(401);
    echo json_encode(['cod' => 0, 'msj' => 'No autorizado', 'icono' => 'error']);
    exit;
}

$modelo = new InscripcionModel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id_alumno = $_GET['id_alumno'] ?? '';
    $tipo      = $_GET['tipo'] ?? '';

    try {
        if (empty($id_alumno)) {
            throw new Exception("ID de alumno no proporcionado");
        }

        if ($tipo === 'inscritos') {
            echo json_encode($modelo->getExamenesInscritos($id_alumno));
        } else if ($tipo === 'disponibles') {
            echo json_encode($modelo->getExamenesDisponibles($id_alumno));
        } else {
            throw new Exception("Tipo no reconocido");
        }
    } catch (Exception $e) {
        error_log("Error en InscripcionController GET: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([]);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion    = $_POST['accion']    ?? '';
    $id_alumno = $_POST['id_alumno'] ?? '';
    $id_examen = $_POST['id_examen'] ?? '';
    $respAX    = [];

    try {
        if (empty($id_alumno) || empty($id_examen)) {
            throw new Exception("Faltan datos");
        }

        switch ($accion) {

            case 'inscribir':
                $resultado = $modelo->inscribir($id_alumno, $id_examen);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Alumno registrado al examen";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo registrar al alumno";
                    $respAX['icono'] = "error";
                }
                break;

            case 'desinscribir':
                $resultado = $modelo->desinscribir($id_alumno, $id_examen);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Registro eliminado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo eliminar el registro";
                    $respAX['icono'] = "error";
                }
                break;

            default:
                throw new Exception("Acción no reconocida");
        }
    } catch (Exception $e) {
        error_log("Error en InscripcionController POST: " . $e->getMessage());
        $respAX['cod']   = 0;
        $respAX['msj']   = "Error al procesar la solicitud";
        $respAX['icono'] = "error";
    }

    echo json_encode($respAX);
}