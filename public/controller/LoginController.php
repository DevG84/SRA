<?php

session_start();
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../model/LoginModel.php';

$correo   = $_POST['correo']   ?? '';
$password = $_POST['password'] ?? '';
$respuesta = [];

try {
    $modelo  = new LoginModel();
    $usuario = $modelo->login($correo, $password);

    if ($usuario) {
        $_SESSION['id_coordinador'] = $usuario['id'];
        $_SESSION['rol']            = $usuario['rol'];
        $_SESSION['nombre']         = $usuario['nombre'] . ' ' . $usuario['apellido_p'];
        $respuesta['cod']       = 1;
        $respuesta['msj']       = "Bienvenido, " . $usuario['nombre'];
        $respuesta['icono']     = "success";
        $respuesta['redirigir'] = $usuario['rol'] === 'gestion'
            ? './GestionDashboard.php'
            : './dashboard.php';
    } else {
        $respuesta['cod']   = 0;
        $respuesta['msj']   = "Correo o contraseña incorrectos";
        $respuesta['icono'] = "error";
    }
} catch (Exception $e) {
    error_log("Error en LoginController: " . $e->getMessage());
    $respuesta['cod']   = 0;
    $respuesta['msj']   = "Error interno del servidor";
    $respuesta['icono'] = "error";
}

echo json_encode($respuesta);