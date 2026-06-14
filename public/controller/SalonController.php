<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../model/SalonModel.php';

// Solo gestión puede modificar — GET es público
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_coordinador']) || ($_SESSION['rol'] ?? '') !== 'gestion') {
        http_response_code(401);
        echo json_encode(['cod' => 0, 'msj' => 'No autorizado', 'icono' => 'error']);
        exit;
    }
}

$modelo = new SalonModel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id'] ?? '';

    try {
        if ($id !== '') {
            echo json_encode($modelo->getSalon($id));
        } else {
            echo json_encode($modelo->getSalones());
        }
    } catch (Exception $e) {
        error_log("Error en SalonController GET: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([]);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['accion'] ?? '';
    $respAX = [];

    try {
        switch ($accion) {

            case 'agregar':
                $resultado = $modelo->addSalon($_POST);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Salón agregado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo agregar el salón";
                    $respAX['icono'] = "error";
                }
                break;

            case 'editar':
                $id        = $_POST['id_salon'] ?? '';
                $resultado = $modelo->updateSalon($id, $_POST);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Salón actualizado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo actualizar el salón";
                    $respAX['icono'] = "error";
                }
                break;

            case 'eliminar':
                $id = $_POST['id_salon'] ?? '';
                if ($modelo->tieneExamenes($id)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se puede eliminar — tiene exámenes asignados";
                    $respAX['icono'] = "warning";
                    break;
                }
                $resultado = $modelo->deleteSalon($id);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Salón eliminado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo eliminar el salón";
                    $respAX['icono'] = "error";
                }
                break;

            default:
                throw new Exception("Acción no reconocida");
        }
    } catch (Exception $e) {
        error_log("Error en SalonController POST: " . $e->getMessage());
        $respAX['cod']   = 0;
        $respAX['msj']   = "Error al procesar la solicitud";
        $respAX['icono'] = "error";
    }

    echo json_encode($respAX);
}