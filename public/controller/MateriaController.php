<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../model/MateriaModel.php';

// Solo gestión puede modificar — GET es público
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_coordinador']) || ($_SESSION['rol'] ?? '') !== 'gestion') {
        http_response_code(401);
        echo json_encode(['cod' => 0, 'msj' => 'No autorizado', 'icono' => 'error']);
        exit;
    }
}

$modelo = new MateriaModel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id          = $_GET['id_materia'] ?? '';
    $id_carrera  = $_GET['carrera']    ?? '';
    $id_semestre = $_GET['semestre']   ?? '';
    $completo    = $_GET['completo']   ?? '';

    try {
        if ($id !== '') {
            echo json_encode($modelo->getMateria($id));
        } else if ($completo) {
            echo json_encode($modelo->getMateriasCompleto());
        } else {
            echo json_encode($modelo->getMaterias($id_carrera, $id_semestre));
        }
    } catch (Exception $e) {
        error_log("Error en MateriaController GET: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([]);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['accion'] ?? '';
    $respAX = [];

    try {
        switch ($accion) {

            case 'agregar':
                $resultado = $modelo->addMateria($_POST);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Materia agregada correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo agregar la materia";
                    $respAX['icono'] = "error";
                }
                break;

            case 'editar':
                $id        = $_POST['id_materia'] ?? '';
                $resultado = $modelo->updateMateria($id, $_POST);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Materia actualizada correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo actualizar la materia";
                    $respAX['icono'] = "error";
                }
                break;

            case 'eliminar':
                $id = $_POST['id_materia'] ?? '';
                if ($modelo->tieneExamenes($id)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se puede eliminar — tiene exámenes asignados";
                    $respAX['icono'] = "warning";
                    break;
                }
                $resultado = $modelo->deleteMateria($id);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Materia eliminada correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo eliminar la materia";
                    $respAX['icono'] = "error";
                }
                break;

            default:
                throw new Exception("Acción no reconocida");
        }
    } catch (Exception $e) {
        error_log("Error en MateriaController POST: " . $e->getMessage());
        $respAX['cod']   = 0;
        $respAX['msj']   = "Error al procesar la solicitud";
        $respAX['icono'] = "error";
    }

    echo json_encode($respAX);
}