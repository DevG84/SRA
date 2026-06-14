<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/PerfilModel.php';

if (!isset($_SESSION['id_coordinador'])) {
    header('Location: ../view/LoginView.php');
    exit();
}

$modelo = new PerfilModel();
$id     = $_SESSION['id_coordinador'];
$rol    = $_SESSION['rol'] ?? 'coordinador';

// POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header("Content-Type: application/json; charset=utf-8");

    $accion = $_POST['accion'] ?? '';
    $respAX = [];

    try {
        switch ($accion) {

            case 'actualizar_datos':
                $nombre     = trim($_POST['nombre']     ?? '');
                $apellido_p = trim($_POST['apellido_p'] ?? '');
                $apellido_m = trim($_POST['apellido_m'] ?? '');
                $correo     = trim($_POST['correo']     ?? '');

                if (empty($nombre) || empty($apellido_p) || empty($correo)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "Nombre, apellido paterno y correo son obligatorios";
                    $respAX['icono'] = "error";
                    break;
                }

                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "El correo no es válido";
                    $respAX['icono'] = "error";
                    break;
                }

                if ($modelo->correoExiste($correo, $rol, $id)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "Ese correo ya está en uso por otra cuenta";
                    $respAX['icono'] = "error";
                    break;
                }

                $modelo->updateDatos($id, $rol, $_POST);

                // Actualizar nombre en sesión para que se refleje en el sidebar
                $_SESSION['nombre'] = $nombre . ' ' . $apellido_p;

                $respAX['cod']   = 1;
                $respAX['msj']   = "Datos actualizados correctamente";
                $respAX['icono'] = "success";
                break;

            case 'cambiar_contrasena':
                $actual  = $_POST['contrasena_actual']    ?? '';
                $nueva   = $_POST['contrasena_nueva']     ?? '';
                $confirm = $_POST['contrasena_confirmar'] ?? '';

                if (empty($actual) || empty($nueva) || empty($confirm)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "Todos los campos de contraseña son obligatorios";
                    $respAX['icono'] = "error";
                    break;
                }

                $hashActual = $modelo->getContrasenaActual($id, $rol);
                if (!password_verify($actual, $hashActual)) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "La contraseña actual es incorrecta";
                    $respAX['icono'] = "error";
                    break;
                }

                if ($nueva !== $confirm) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "Las contraseñas nuevas no coinciden";
                    $respAX['icono'] = "error";
                    break;
                }

                if (strlen($nueva) < 6) {
                    $respAX['cod']   = 0;
                    $respAX['msj']   = "La nueva contraseña debe tener al menos 6 caracteres";
                    $respAX['icono'] = "error";
                    break;
                }

                $modelo->updateContrasena($id, $rol, $nueva);

                $respAX['cod']   = 1;
                $respAX['msj']   = "Contraseña actualizada correctamente";
                $respAX['icono'] = "success";
                break;

            default:
                throw new Exception("Acción no reconocida");
        }
    } catch (Exception $e) {
        error_log("Error en PerfilController POST: " . $e->getMessage());
        $respAX['cod']   = 0;
        $respAX['msj']   = "Error al procesar la solicitud";
        $respAX['icono'] = "error";
    }

    echo json_encode($respAX);
    exit;
}

//GET

$coordinador = $modelo->getPerfil($id, $rol);

if (!$coordinador) {
    header('Location: ../view/LoginView.php');
    exit();
}