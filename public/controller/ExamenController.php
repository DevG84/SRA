<?php

    // Pasamos Model
    session_start();
    require_once __DIR__ . '/../model/ExamenModel.php';

    $model = new ExamenModel();

    // Metodo GET

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        // Obtener Parametros
        $carrera = $_GET['carrera'] ?? '';
        $semestre = $_GET['semestre'] ?? '';
        $materia = $_GET['materia'] ?? '';
        $texto = $_GET['texto'] ?? '';

        //Limpiamos datos
        $carrera = trim($carrera);
        $semestre = trim($semestre);
        $materia = trim($materia);
        $texto = trim($texto);

        // Llamamos al modelo
        try {
            $examenes = $model -> getExamenes($carrera, $semestre, $materia, $texto);
            echo json_encode($examenes);
        } catch (Exception $e) {
            error_log("Error en ExamenController: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([ "msj" => "Error al obtener los exámenes" ]);
        }
    }else if($_SERVER['REQUEST_METHOD'] === 'POST'){

        //METODO POST

        if (!isset($_SESSION['id_coordinador'])) {
            http_response_code(401);
            echo json_encode([ "msj" => "No autorizado" ]);
            exit;
        }

        $accion = $_POST['accion'] ?? '';
        $respuesta = [];

        try{
            switch ($accion) {
                
                case 'eliminar':
                    $id_examen = $_POST['id_examen'] ?? '';
                    if (empty($id_examen)) {
                        throw new Exception("ID de examen no proporcionado");
                    }
                    $resultado = $model->deleteExamen($id_examen);
                    if ($resultado) {
                        $respuesta['cod'] = 1;
                        $respuesta['msj'] = "Examen eliminado correctamente";
                        $respuesta['icono'] = "success";
                    } else {
                        $respuesta['cod'] = 0;
                        $respuesta['msj'] = "No se pudo eliminar el examen";
                        $respuesta['icono'] = "error";
                    }
                    break;

                case 'crear':

                    function guardarArchivo($archivo) {

                        if (!isset($_FILES[$archivo]) || $_FILES[$archivo]['error'] === UPLOAD_ERR_NO_FILE) {
                            return null; // no se subió archivo
                        }

                        if($_FILES[$archivo]['type'] !== "application/pdf") {
                            throw new Exception("El archivo debe ser pdf");
                        }

                        $nombreArchivo = uniqid($archivo . "_") . ".pdf";
                        $rutaDestino = __DIR__ . "/../uploads/" . $nombreArchivo;
                        
                        if (move_uploaded_file($_FILES[$archivo]['tmp_name'], $rutaDestino)) {
                            return 'uploads/' . $nombreArchivo; // se guardó correctamente
                        } else {
                            throw new Exception("Error al guardar el archivo");
                        }
                    }

                    $datos = $_POST;
                    $datos['guia'] = guardarArchivo('guia');
                    $datos['proyecto'] = guardarArchivo('proyecto');
                    $resultado = $model->addExamen($datos);

                    if ($resultado) {
                        $respuesta['cod'] = 1;
                        $respuesta['msj'] = "Examen agregado correctamente";
                        $respuesta['icono'] = "success";
                    } else {
                        $respuesta['cod'] = 0;
                        $respuesta['msj'] = "No se pudo agregar el examen";
                        $respuesta['icono'] = "error";
                    }
                    break;

                case 'editar':
                    $id_examen = $_POST['id_examen'] ?? '';
                    if (empty($id_examen)) {
                        throw new Exception("ID de examen no proporcionado");
                    }
                    $datos = $_POST;
                    $datos['guia'] = guardarArchivo('guia');
                    $datos['proyecto'] = guardarArchivo('proyecto');
                    $resultado = $model->updateExamen($id_examen, $datos);

                    if ($resultado) {
                        $respuesta['cod'] = 1;
                        $respuesta['msj'] = "Examen actualizado";
                        $respuesta['icono'] = "success";
                    } else {
                        $respuesta['cod'] = 0;
                        $respuesta['msj'] = "No se pudo actualizar el examen";
                        $respuesta['icono'] = "error";
                    }
                    break;
                
                default:
                    throw new Exception("Acción no reconocida");
            }
        } catch (Exception $e) {
            error_log("Error en ExamenController POST: " . $e->getMessage());
            $respuesta['cod'] = 0;
            $respuesta['msj'] = "Error al procesar la solicitud";
            $respuesta['icono'] = "error";
        }
        echo json_encode($respuesta);
    }