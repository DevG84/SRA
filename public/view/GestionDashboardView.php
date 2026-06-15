<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/auth.php';

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
  <title>Gestión — SRA ESCOM</title>
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
  <script defer src="../assets/js/gestion.js"></script>
</head>
<body>

  <?php
  $paginaActiva = 'gestion';
  include __DIR__ . '/../partials/sidebar.php';
  ?>

  <div id="main-content">

    <!-- Tabla de Salones -->
    <section id="seccion-tabla" style="margin-bottom:1.5rem;">
      <div class="tabla-header">
        <h2><i class="fa-solid fa-door-open"></i> Salones</h2>
        <div style="display:flex; gap:0.75rem; align-items:center;">
          <input type="text" id="filtro-salon-texto" class="form-control form-control-sm"
                 placeholder="Buscar salón..." style="width:180px;">
          <button class="btn-accion" onclick="mostrarFormSalon()">
            <i class="fa-solid fa-plus"></i> Nuevo salón
          </button>
        </div>
      </div>

      <!-- Formulario salón (oculto por default) -->
      <div id="form-salon-contenedor" style="display:none; margin-bottom:1rem;">
        <form id="form-salon" autocomplete="off">
          <div class="row g-3">
            <div class="col-12 col-md-3">
              <label>Edificio <span class="text-muted">(1 dígito)</span></label>
              <input type="text" id="salon-edificio" name="edificio" class="form-control"
                     placeholder="ej. 1" maxlength="1">
            </div>
            <div class="col-12 col-md-3">
              <label>Número de salón <span class="text-muted">(3 dígitos)</span></label>
              <input type="text" id="salon-numero" name="salon" class="form-control"
                     placeholder="ej. 001" maxlength="3">
            </div>
            <div class="col-12 col-md-3 d-flex align-items-end">
              <div class="form-check">
                <input type="checkbox" id="salon-laboratorio" name="laboratorio" class="form-check-input">
                <label class="form-check-label" for="salon-laboratorio">Es laboratorio</label>
              </div>
            </div>
            <div class="col-12 col-md-3 d-flex align-items-end gap-2">
              <button type="submit" class="btn-accion">
                <i class="fa-solid fa-floppy-disk"></i> Guardar
              </button>
              <button type="button" class="btn btn-secondary btn-sm" onclick="cancelarFormSalon()">
                Cancelar
              </button>
            </div>
          </div>
          <input type="hidden" id="salon-accion" name="accion" value="agregar">
          <input type="hidden" id="salon-id" name="id_salon" value="">
        </form>
      </div>

      <div class="tabla-contenedor">
        <table class="tabla">
          <thead>
            <tr>
              <th hidden>#</th>
              <th>Salón</th>
              <th>Tipo</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tbody-salones">
            <tr><td colspan="4" class="texto-center">Cargando...</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Tabla de Materias -->
    <section id="seccion-tabla">
      <div class="tabla-header">
        <h2><i class="fa-solid fa-book"></i> Materias</h2>
        <div style="display:flex; gap:0.75rem; align-items:center;">
          <input type="text" id="filtro-materia-texto" class="form-control form-control-sm"
                 placeholder="Buscar materia..." style="width:180px;">
          <a href="./CrearMateriaView.php" class="btn-accion">
            <i class="fa-solid fa-plus"></i> Nueva materia
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
              <th>Semestre</th>
              <th>Departamento</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tbody-materias">
            <tr><td colspan="6" class="texto-center">Cargando...</td></tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>

</body>
</html>