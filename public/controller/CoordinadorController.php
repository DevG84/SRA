<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../model/CoordinadorModel.php';

// Solo validar si es un usuario de gestión
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['verificar'])) {
    if (isset($_SESSION['rol']) === 'gestion') {
        echo json_encode(['es_gestion' => true]);
    } else {
        echo json_encode(['es_gestion' => false]);
    }
    exit;
}

// Solo gestión puede usar este controller
if ($_SESSION['rol'] !== 'gestion') {
    http_response_code(401);
    echo json_encode(['cod' => 0, 'msj' => 'No autorizado', 'icono' => 'error']);
    exit;
}

$modelo = new CoordinadorModel();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id'] ?? '';

    try {
        if ($id !== '') {
            // Traer un coordinador por ID
            $coordinador = $modelo->getCoordinador($id);
            echo json_encode($coordinador);
        } else {
            // Traer todos
            $coordinadores = $modelo->getCoordinadores();
            echo json_encode($coordinadores);
        }
    } catch (Exception $e) {
        error_log("Error en CoordinadorController GET: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([]);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['accion'] ?? '';
    $respAX = [];

    try {
        switch ($accion) {
            case 'agregar':
                $resultado = $modelo->addCoordinador($_POST);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Coordinador registrado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo registrar el coordinador";
                    $respAX['icono'] = "error";
                }
                break;

            case 'editar':
                $id        = $_POST['id_coordinador'] ?? '';
                $resultado = $modelo->updateCoordinador($id, $_POST);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Coordinador actualizado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo actualizar el coordinador";
                    $respAX['icono'] = "error";
                }
                break;

            case 'eliminar':
                $id        = $_POST['id_coordinador'] ?? '';
                $resultado = $modelo->deleteCoordinador($id);
                if ($resultado) {
                    $respAX['cod']   = 1;
                    $respAX['msj']   = "Coordinador eliminado correctamente";
                    $respAX['icono'] = "success";
                } else {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "No se pudo eliminar el coordinador";
                    $respAX['icono'] = "error";
                }
                break;

            default:
                throw new Exception("Acción no reconocida");
        }
    } catch (Exception $e) {
        error_log("Error en CoordinadorController POST: " . $e->getMessage());
        $respAX['cod']   = 0;
        $respAX['msj']   = "Error al procesar la solicitud";
        $respAX['icono'] = "error";
    }

    echo json_encode($respAX);
}