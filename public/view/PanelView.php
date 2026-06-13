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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />

    <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
    
    <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
    
    <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>

    <script defer src="../assets/js/dashboard.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <?php 
            $paginaActiva = 'panel'; // cambia según la vista
            include __DIR__ . '/../partials/sidebar.php'; 
        ?>

<div>
    <!-- TODO: Crear el panel de control de los coordinadores -->
</div>

</body>
</html>