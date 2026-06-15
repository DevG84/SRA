<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

// Obtener el ID del examen desde la URL
$id_examen = $_GET['id'] ?? null;

if (!$id_examen) {
    header('Location: ./DashboardView.php');
    exit;
}

// Cargar los datos del examen para prellenar el formulario
$conn  = (new Connection)->connect();
$stmt  = $conn->prepare("SELECT * FROM examen WHERE id_examen = :id");
$stmt->execute([':id' => $id_examen]);
$examen = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$examen) {
    header('Location: ./DashboardView.php');
    exit;
}

if ($_SESSION['rol'] !== 'gestion' && $examen['id_coordinador'] != $_SESSION['id_coordinador']) {
    header('Location: ./DashboardView.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Editar Examen — SRA ESCOM</title>
  <link rel="icon" type="image/svg+xml" href="../assets/img/sra_logo.svg">
  <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/crearExamen.css">

  <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>
</head>

<body>

  <?php 
        $paginaActiva = ''; // cambia según la vista
        include __DIR__ . '/../partials/sidebar.php'; 
    ?>

  <main id="main-content">

      <a class="btn btn-dark btn-lg h3 back" href="./PanelView.php">
        <span class="material-symbols-outlined">arrow_back_ios</span>
          Regresar
      </a>

      <h2><i class="fa-solid fa-calendar-pen"></i> Editar Examen ETS</h2>

      <div id="contenedor-form">
      
      <form id="form-editar-examen" autocomplete="off" enctype="multipart/form-data">

        <div class="row g-3">

          <!-- Carrera -->
          <div class="col-12 col-md-4">
            <label for="id_carrera">Carrera</label>
            <select id="id_carrera" name="id_carrera" class="form-select">
              <option value="">Seleccionar Carrera</option>
            </select>
          </div>

          <!-- Semestre -->
          <div class="col-12 col-md-4">
            <label for="id_semestre">Semestre</label>
            <select id="id_semestre" name="id_semestre" class="form-select">
              <option value="">Seleccionar Semestre</option>
              <option value="1">1° semestre</option>
              <option value="2">2° semestre</option>
              <option value="3">3° semestre</option>
              <option value="4">4° semestre</option>
              <option value="5">5° semestre</option>
              <option value="6">6° semestre</option>
              <option value="7">7° semestre</option>
              <option value="8">8° semestre</option>
            </select>
          </div>

          <!-- Materia -->
          <div class="col-12 col-md-4">
            <label for="id_materia">Materia</label>
            <select id="id_materia" name="id_materia" class="form-select">
              <option value="">Seleccionar Materia</option>
            </select>
          </div>

          <!-- Salón -->
          <div class="col-12 col-md-4">
            <label for="id_salon">Salón</label>
            <select id="id_salon" name="id_salon" class="form-select">
              <option value="">Seleccionar Salón</option>
            </select>
          </div>

          <!-- Fecha — value prellenado -->
          <div class="col-12 col-md-4">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" class="form-control"
                   value="<?php echo $examen['fecha']; ?>">
          </div>

          <!-- Turno — selected prellenado -->
          <div class="col-12 col-md-4">
            <label for="turno">Turno</label>
            <select id="turno" name="turno" class="form-select">
              <option value="">Seleccionar Turno</option>
              <option value="Matutino"   <?php echo $examen['turno'] === 'Matutino'   ? 'selected' : ''; ?>>Matutino</option>
              <option value="Vespertino" <?php echo $examen['turno'] === 'Vespertino' ? 'selected' : ''; ?>>Vespertino</option>
            </select>
          </div>

          <!-- Horario — value prellenado -->
          <div class="col-12 col-md-6">
            <label for="horario">Horario</label>
            <input type="text" id="horario" name="horario" class="form-control"
                   placeholder="ej. 8:00 a 10:00"
                   value="<?php echo htmlspecialchars($examen['horario']); ?>">
          </div>

          <input type="hidden" id="data-id_coordinador" value="<?php echo $examen['id_coordinador']; ?>">

          <?php if ($_SESSION['rol'] === 'gestion'): ?>
            <div class="col-12 col-md-6">
              <label for="id_coordinador">Asignar a Coordinador</label>
              <select id="id_coordinador" name="id_coordinador" class="form-select">
                <option value="">Seleccionar Coordinador</option>
              </select>
            </div>
          <?php else: ?>
            <input type="hidden" name="id_coordinador" value="<?php echo $_SESSION['id_coordinador']; ?>">
          <?php endif; ?>

          <!-- Nota — value prellenado -->
          <div class="col-12 col-md-6">
            <label for="nota">Nota <span class="text-muted">(opcional)</span></label>
            <input type="text" id="nota" name="nota" class="form-control"
                   value="<?php echo htmlspecialchars($examen['nota'] ?? ''); ?>">
          </div>

        <!-- Guía -->
        <div class="col-12 col-md-6">
        <label for="guia">Guía <span class="text-muted">(opcional, PDF)</span></label>
        <?php if ($examen['guia']): ?>
            <p id="guia-actual-p" class="text-muted" style="font-size:0.85rem;">
            Archivo actual: <a href="../<?php echo $examen['guia']; ?>" target="_blank">Ver guía</a>
            <button type="button" class="btn btn-sm btn-danger ms-2"
                    onclick="limpiarArchivo('guia', 'guia-actual', 'guia-actual-p')">
                <i class="fa-solid fa-trash"></i> Quitar
            </button>
            </p>
            <input type="hidden" name="guia_actual" id="guia-actual" value="<?php echo $examen['guia']; ?>">
        <?php endif; ?>
        <input type="file" id="guia" name="guia" class="form-control" accept=".pdf">
        </div>

        <!-- Proyecto -->
        <div class="col-12 col-md-6">
        <label for="proyecto">Proyecto <span class="text-muted">(opcional, PDF)</span></label>
        <?php if ($examen['proyecto']): ?>
            <p id="proyecto-actual-p" class="text-muted" style="font-size:0.85rem;">
            Archivo actual: <a href="../<?php echo $examen['proyecto']; ?>" target="_blank">Ver proyecto</a>
            <button type="button" class="btn btn-sm btn-danger ms-2"
                    onclick="limpiarArchivo('proyecto', 'proyecto-actual', 'proyecto-actual-p')">
                <i class="fa-solid fa-trash"></i> Quitar
            </button>
            </p>
            <input type="hidden" name="proyecto_actual" id="proyecto-actual" value="<?php echo $examen['proyecto']; ?>">
        <?php endif; ?>
        <input type="file" id="proyecto" name="proyecto" class="form-control" accept=".pdf">
        </div>

        </div>

        <!-- accion editar e id del examen -->
        <input type="hidden" name="accion"     value="editar">
        <input type="hidden" name="id_examen"  value="<?php echo $id_examen; ?>">
        <!-- datos del examen para prellenar selects dinámicos con JS -->
        <input type="hidden" id="data-id_materia" value="<?php echo $examen['id_materia']; ?>">
        <input type="hidden" id="data-id_salon"   value="<?php echo $examen['id_salon']; ?>">

        <div class="mt-3">
          <button type="submit" id="btn-crear">
            <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
          </button>
        </div>

      </form>
    </div>
  </main>

  <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
  <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
  <script src="../assets/js/libs/JustValidate/justValidate.min.js"></script>
  <!-- JS de editar -->
  <script defer src="../assets/js/editarExamen.js"></script>

</body>
</html>