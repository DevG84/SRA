<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../model/AlumnoModel.php';

// Solo gestión puede usar este controller
if (!isset($_SESSION['id_coordinador']) || ($_SESSION['rol'] ?? '') !== 'gestion') {
    http_response_code(401);
    echo json_encode(['cod' => 0, 'msj' => 'No autorizado', 'icono' => 'error']);
    exit;
}

$modelo = new AlumnoModel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id'] ?? '';

    try {
        if ($id !== '') {
            echo json_encode($modelo->getAlumno($id));
        } else {
            echo json_encode($modelo->getAlumnos());
        }
    } catch (Exception $e) {
        error_log("Error en AlumnoController GET: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([]);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['accion'] ?? '';
    $respAX = [];

    try {
        switch ($accion) {

            case 'agregar':
                $boleta = $_POST['boleta'] ?? '';

                // Validar formato de boleta — 10 dígitos
                if (!preg_match('/^\d{10}$/', $boleta)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "La boleta debe tener 10 dígitos";
                    $respAX['icono'] = "error";
                    break;
                }

                // Validar que no exista
                if ($modelo->boletaExiste($boleta)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "Ya existe un alumno con esa boleta";
                    $respAX['icono'] = "error";
                    break;
                }

                $resultado = $modelo->addAlumno($_POST);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Alumno registrado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo registrar el alumno";
                    $respAX['icono'] = "error";
                }
                break;

            case 'editar':
                $id     = $_POST['id_alumno'] ?? '';
                $boleta = $_POST['boleta'] ?? '';

                if (!preg_match('/^\d{10}$/', $boleta)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "La boleta debe tener 10 dígitos";
                    $respAX['icono'] = "error";
                    break;
                }

                // Validar que no exista en otro alumno
                if ($modelo->boletaExiste($boleta, $id)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "Ya existe otro alumno con esa boleta";
                    $respAX['icono'] = "error";
                    break;
                }

                $resultado = $modelo->updateAlumno($id, $_POST);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Alumno actualizado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo actualizar el alumno";
                    $respAX['icono'] = "error";
                }
                break;

            case 'eliminar':
                $id = $_POST['id_alumno'] ?? '';

                if ($modelo->tieneExamenes($id)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se puede eliminar — el alumno tiene exámenes registrados";
                    $respAX['icono'] = "warning";
                    break;
                }

                $resultado = $modelo->deleteAlumno($id);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Alumno eliminado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo eliminar el alumno";
                    $respAX['icono'] = "error";
                }
                break;

            default:
                throw new Exception("Acción no reconocida");
        }
    } catch (Exception $e) {
        error_log("Error en AlumnoController POST: " . $e->getMessage());
        $respAX['cod']   = 0;
        $respAX['msj']   = "Error al procesar la solicitud";
        $respAX['icono'] = "error";
    }

    echo json_encode($respAX);
}