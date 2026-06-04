<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

    session_start();

    // Pasamos Model
    require_once __DIR__ . '/../model/LoginModel.php';

    // Obtener Parametros
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    $respuesta = [];

    try {
        // Instanciar LoginModel
        $modelo      = new LoginModel();
        $coordinador = $modelo->login($correo, $password);

        if ($coordinador) {
            $_SESSION['id_coordinador'] = $coordinador['id_coordinador'];
            $respuesta['cod']   = 1;
            $respuesta['msj']   = "Bienvenido, " . $coordinador['nombre'];
            $respuesta['icono'] = "success";
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
