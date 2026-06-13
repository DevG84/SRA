<?php
session_start();

if (!isset($_SESSION['id_coordinador'])) {
    header('Location: ./LoginView.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel principal</title>
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
<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary sidebar-transition sidebar-collapsed" style="height: 100vh;">
  
    <a id="toggle-sidebar" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none" style="cursor: pointer;">
        <span class="material-symbols-outlined me-2" style="font-size: 32px;">page_menu_ios</span>
        <span class="fs-4 nav-text">Sistema SRA</span>
    </a>

    <hr>

    <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
        <a href="" class="nav-link active d-flex align-items-center" aria-current="page">
        <span class="material-symbols-outlined me-2">home</span>
        <span class="nav-text">Inicio</span>
        </a>
    </li>
    <li>
        <a href="./PanelView.php" class="nav-link link-body-emphasis d-flex align-items-center">
        <span class="material-symbols-outlined me-2">dashboard</span>
        <span class="nav-text">Panel de Control</span>
        </a>
    </li>
    <li>
        <a href="#" class="nav-link link-body-emphasis d-flex align-items-center">
        <span class="material-symbols-outlined me-2">lab_profile</span>
        <span class="nav-text">Reportes</span>
        </a>
    </li>
    <li>
        <a href="#" class="nav-link link-body-emphasis d-flex align-items-center">
        <span class="material-symbols-outlined me-2">manage_accounts</span>
        <span class="nav-text">Coordinadores</span>
        </a>
    </li>
    <li>
        <a href="#" class="nav-link link-body-emphasis d-flex align-items-center">
        <span class="material-symbols-outlined me-2">history_edu</span>
        <span class="nav-text">Alumnos</span>
        </a>
    </li>
    </ul>

    <hr>

    <div class="dropdown">
    <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="material-symbols-outlined me-2">account_circle</span>
        <strong class="nav-text"><?php echo $_SESSION['nombre'] ?? 'Desconocido'; ?></strong>
    </a>
    <ul class="dropdown-menu text-small shadow">
        <li>
        <a class="dropdown-item d-flex align-items-center" href="#">
            <span class="material-symbols-outlined me-2" style="font-size: 18px;">person</span> <span class="ms-2">Perfil</span>
        </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
        <a class="dropdown-item d-flex align-items-center text-danger" href="../controller/CerrarSesionController.php">
            <span class="material-symbols-outlined me-2" style="font-size: 18px;">logout</span> <span class="ms-2">Cerrar Sesión</span>
        </a>
        </li>
    </ul>
    </div>
</div>

<div>
    <!-- TODO: Crear el Dashboard -->
</div>

</body>
</html>