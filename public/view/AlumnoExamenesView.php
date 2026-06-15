<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/auth.php';

if ($_SESSION['rol'] !== 'gestion') {
    header('Location: ./DashboardView.php');
    exit;
}

$id_alumno = $_GET['id'] ?? null;

if (!$id_alumno) {
    header('Location: ./AlumnosView.php');
    exit;
}

require_once __DIR__ . '/../../includes/db.php';
$conn = (new Connection)->connect();

$stmt = $conn->prepare("SELECT * FROM alumno WHERE id_alumno = :id");
$stmt->execute([':id' => $id_alumno]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    header('Location: ./AlumnosView.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscripciones — SRA ESCOM</title>
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
  <script defer src="../assets/js/alumnoExamenes.js"></script>
</head>
<body>

  <?php
  $paginaActiva = 'alumnos';
  include __DIR__ . '/../partials/sidebar.php';
  ?>

  <div id="main-content">

    <div style="margin-bottom:1.5rem;">
      <h2 style="font-size:1.1rem; font-weight:600; margin:0;">
        <i class="fa-solid fa-user-graduate"></i>
        <?php echo htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido_p'] . ' ' . ($alumno['apellido_m'] ?? '')); ?>
      </h2>
      <p class="text-muted" style="margin:0;">Boleta: <?php echo htmlspecialchars($alumno['boleta']); ?></p>
    </div>

    <!-- Exámenes inscritos -->
    <section id="seccion-tabla" style="margin-bottom:1.5rem;">
      <div class="tabla-header">
        <h2><i class="fa-solid fa-check-circle"></i> Exámenes registrados</h2>
      </div>

      <div class="tabla-contenedor">
        <table class="tabla">
          <thead>
            <tr>
              <th>Materia</th>
              <th>Carrera</th>
              <th>Fecha</th>
              <th>Turno</th>
              <th>Salón</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tbody-inscritos">
            <tr><td colspan="6" class="texto-center">Cargando...</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Exámenes disponibles -->
    <section id="seccion-tabla">
      <div class="tabla-header">
        <h2><i class="fa-solid fa-list"></i> Exámenes disponibles</h2>
        <input type="text" id="filtro-disponibles-texto" class="form-control form-control-sm"
               placeholder="Buscar examen..." style="width:220px;">
      </div>

      <div class="tabla-contenedor">
        <table class="tabla">
          <thead>
            <tr>
              <th>Materia</th>
              <th>Carrera</th>
              <th>Fecha</th>
              <th>Turno</th>
              <th>Salón</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="tbody-disponibles">
            <tr><td colspan="6" class="texto-center">Cargando...</td></tr>
          </tbody>
        </table>
      </div>
    </section>

  </div>

  <!-- ID del alumno para el JS -->
  <input type="hidden" id="data-id_alumno" value="<?php echo $id_alumno; ?>">

</body>
</html>