<?php
session_start();

require_once __DIR__ . '/../../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control</title>
    <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/theme.scss">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/gestionDashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
    <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
    <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>
    <script defer src="../assets/js/dashboard.js"></script>
    <script defer src="../assets/js/panel.js"></script>
</head>
<body>
    <?php 
            $paginaActiva = 'panel'; // cambia según la vista
            include __DIR__ . '/../partials/sidebar.php'; 
        ?>

<div id="main-content" data-coordinador="<?php echo $_SESSION['id_coordinador']; ?>">

    <div id="seccion-tabla">
        <div class="tabla-header">
            <h2><i class="fa-solid fa-calendar-days"></i> Mis Exámenes ETS</h2>
            <div class="d-flex gap-2 align-items-center">
                <input type="text" id="filtro-panel-texto" class="form-control form-control-sm"
                       placeholder="Buscar examen..." style="width: 220px;">
                <a href="./CrearExamenView.php" class="btn-accion">
                    <i class="fa-solid fa-plus"></i> Nuevo examen
                </a>
            </div>
        </div>

        <div class="tabla-contenedor">
            <table class="tabla">
                <thead>
                    <tr>
                        <th hidden>#</th>
                        <th>Materia</th>
                        <th>Carrera</th>
                        <th>Fecha</th>
                        <th>Turno</th>
                        <th>Horario</th>
                        <th>Salón</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-panel">
                    <tr><td colspan="8" class="texto-center">Cargando...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>