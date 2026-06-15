<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/auth.php';
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes — SRA ESCOM</title>
  <link rel="icon" type="image/svg+xml" href="../assets/img/sra_logo.svg">
  <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/dashboard.css">
  <link rel="stylesheet" href="../assets/css/gestionDashboard.css">
  <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
  <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
  <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>
  <script defer src="../assets/js/dashboard.js"></script>
  <script defer src="../assets/js/reportes.js"></script>
</head>
<body>

  <?php
  $paginaActiva = 'reportes';
  include __DIR__ . '/../partials/sidebar.php';
  ?>

  <div id="main-content">

    <section id="seccion-tabla">
      <div class="tabla-header">
        <h2><i class="fa-solid fa-lab-profile"></i> Reportes de Exámenes</h2>
        <input type="text" id="filtro-reporte-texto" class="form-control form-control-sm"
               placeholder="Buscar examen..." style="width:220px;">
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
              <th>Salón</th>
              <th>Coordinador</th>
              <th>Alumnos</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tbody-reportes">
            <tr><td colspan="9" class="texto-center">Cargando...</td></tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>

  <!-- Modal de alumnos inscritos -->
  <div class="modal fade" id="modal-alumnos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-titulo">Alumnos registrados</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="tabla-contenedor">
            <table class="tabla">
              <thead>
                <tr>
                  <th>Boleta</th>
                  <th>Nombre</th>
                </tr>
              </thead>
              <tbody id="tbody-alumnos">
                <tr><td colspan="2" class="texto-center">Cargando...</td></tr>
              </tbody>
            </table>
          </div>
        <div class="modal-footer">
            <a id="btn-exportar-pdf" href="#" target="_blank" class="btn-accion">
                <i class="fa-solid fa-file-pdf"></i> Exportar PDF
            </a>
        </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>