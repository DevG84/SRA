<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/auth.php';

// Solo gestión puede entrar
if ($_SESSION['rol'] !== 'gestion') {
    header('Location: ./DashboardView.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coordinadores — SRA ESCOM</title>
  <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/dashboard.css">
  <link rel="stylesheet" href="../assets/css/gestionDashboard.css">
  <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
  <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
  <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>
  <script defer src="../assets/js/dashboard.js"></script>
  <script defer src="../assets/js/coordinadores.js"></script>
</head>
<body>

  <?php
    $paginaActiva = 'coordinadores';
    include __DIR__ . '/../partials/sidebar.php';
  ?>

  <div id="main-content">

    <section id="seccion-tabla">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; padding-bottom:0.75rem; border-bottom:2px solid var(--bs-border-color);">
        <h2 style="margin:0; font-size:1rem; font-weight:600;">Coordinadores</h2>
        <a href="./CrearCoordinadorView.php" class="btn-accion">
          <i class="fa-solid fa-plus"></i> Nuevo coordinador
        </a>
      </div>

      <div class="tabla-contenedor">
        <table class="tabla">
          <thead>
            <tr>
                <th hidden>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Departamento</th>
                <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tbody-coordinadores">
            <tr><td colspan="5" class="texto-center">Cargando...</td></tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>

</body>
</html>