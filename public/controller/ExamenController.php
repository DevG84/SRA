<?php

    // Pasamos Model
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../model/ExamenModel.php';

    $model = new ExamenModel();

    function guardarArchivo($archivo, $subcarpeta) {
        if (!isset($_FILES[$archivo]) || $_FILES[$archivo]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES[$archivo]['type'] !== "application/pdf") {
            throw new Exception("El archivo debe ser PDF");
        }

        $nombreArchivo = uniqid($archivo . "_") . ".pdf";
        $rutaDestino   = __DIR__ . "/../uploads/" . $subcarpeta . "/" . $nombreArchivo;

        if (move_uploaded_file($_FILES[$archivo]['tmp_name'], $rutaDestino)) {
            return 'uploads/' . $subcarpeta . '/' . $nombreArchivo;
        } else {
            throw new Exception("Error al guardar el archivo $archivo");
        }            
    }

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
        $origen   = trim($_GET['origen'] ?? '');

        $id_coordinador = "";
        $rolActual = $_SESSION['rol'] ?? ''; 

        if ($rolActual !== 'gestion' && $origen !== 'publico') {
            if (isset($_SESSION['id_coordinador'])) {
                $id_coordinador = $_SESSION['id_coordinador'];
            }
        }

        // Llamamos al modelo
        try {
            $examenes = $model -> getExamenes($carrera, $semestre, $materia, $texto, $id_coordinador);
            echo json_encode($examenes);
        } catch (Exception $e) {
            error_log("Error en ExamenController: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([ "msj" => "Error al obtener los exámenes" ]);
        }
    }else if($_SERVER['REQUEST_METHOD'] === 'POST'){

        //METODO POST

        if (!isset($_SESSION['id_coordinador']) && !isset($_SESSION['id_gestion'])) {
            http_response_code(401);
            echo json_encode([ "msj" => "No autorizado." ]);
            exit;
        }

        $accion = $_POST['accion'] ?? '';
        $respuesta = [];

        try{
            switch ($accion) {
                
                case 'eliminar':

                    $id_examen = $_POST['id_examen'] ?? '';

                    if (!isset($_SESSION['id_gestion'])) {
                        $examenActual = $model->getExamen($id_examen);
                        if (!$examenActual || $examenActual['id_coordinador'] != $_SESSION['id_coordinador']) {
                            $respuesta['cod'] = 0;
                            $respuesta['msj'] = "No tienes permiso para eliminar este examen.";
                            $respuesta['icono'] = "error";
                            echo json_encode($respuesta);
                            exit;
                        }
                    }

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

                case 'agregar':

                    $datos = $_POST;
                    $datos['guia'] = guardarArchivo('guia', 'guias');
                    $datos['proyecto'] = guardarArchivo('proyecto', 'proyectos');
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

                    if (!isset($_SESSION['id_gestion'])) { 
                        $examenActual = $model->getExamen($id_examen);
                    
                        $examenActual = $model->getExamen($id_examen);
                        if (!$examenActual || $examenActual['id_coordinador'] != $_SESSION['id_coordinador']) {
                            $respuesta['cod']   = 0;
                            $respuesta['msj']   = "No tienes permiso para editar este examen";
                            $respuesta['icono'] = "error";
                            break;
                        }
                    }

                    $datos = $_POST;
                    // Si no se subió archivo nuevo, conservar el actual
                    $datos['guia'] = guardarArchivo('guia', 'guias') ?? ($_POST['guia_actual'] ?? null);
                    $datos['proyecto'] = guardarArchivo('proyecto', 'proyectos') ?? ($_POST['proyecto_actual'] ?? null);
                    
                    if (!isset($_SESSION['id_gestion'])) {
                        $datos['id_coordinador'] = $_SESSION['id_coordinador'];
                        }
                        
                    $resultado = $model -> updateExamen($id_examen, $datos);
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