<?php
session_start();

if (!isset($_SESSION['id_coordinador']) || isset($_SESSION['id_gestion'])) {
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
    <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
    <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>
    <script defer src="../assets/js/dashboard.js"></script>
</head>
<body>

<?php 
    $paginaActiva = 'inicio';
    include __DIR__ . '/../partials/sidebar.php'; 
?>

<main>

    <div id="bienvenida">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h2>
        <p class="text-muted">Panel de control — Sistema de Regularización Académica</p>
    </div>

    <div class="row g-3" id="stats-container">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="fa-solid fa-calendar-check stat-icon"></i>
                <span class="stat-numero" id="stat-examenes">—</span>
                <span class="stat-label">Exámenes ETS</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="fa-solid fa-user-tie stat-icon"></i>
                <span class="stat-numero" id="stat-coordinadores">—</span>
                <span class="stat-label">Coordinadores</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="fa-solid fa-graduation-cap stat-icon"></i>
                <span class="stat-numero" id="stat-carreras">—</span>
                <span class="stat-label">Carreras</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <i class="fa-solid fa-door-open stat-icon"></i>
                <span class="stat-numero" id="stat-salones">—</span>
                <span class="stat-label">Salones</span>
            </div>
        </div>
    </div>

    <div id="accesos-rapidos">
        <h5>Accesos rápidos</h5>
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4">
                <a href="./CrearExamenView.php" class="acceso-card">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>Crear Examen</span>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <a href="./ReportesView.php" class="acceso-card">
                    <i class="fa-solid fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <a href="/public/view/ConsultaView.php" class="acceso-card">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Consulta Pública ETS</span>
                </a>
            </div>
        </div>
    </div>

</main>

</body>
</html>