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
  <title>Editar Alumno — SRA ESCOM</title>
  <link rel="icon" type="image/svg+xml" href="../assets/img/sra_logo.svg">
  <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/dashboard.css">
  <link rel="stylesheet" href="../assets/css/crearExamen.css">
  <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
  <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
  <script src="../assets/js/libs/JustValidate/justValidate.min.js"></script>
  <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>
  <script defer src="../assets/js/dashboard.js"></script>
  <script defer src="../assets/js/editarAlumno.js"></script>
</head>
<body>

  <?php
  $paginaActiva = 'alumnos';
  include __DIR__ . '/../partials/sidebar.php';
  ?>

  <div id="main-content">
    <div id="contenedor-form">
      <h2><i class="fa-solid fa-user-pen"></i> Editar Alumno</h2>

      <form id="form-editar-alumno" autocomplete="off">

        <div class="row g-3">

          <div class="col-12 col-md-4">
            <label for="boleta">Boleta <span class="text-muted">(10 dígitos)</span></label>
            <input type="text" id="boleta" name="boleta" class="form-control"
                   maxlength="10" value="<?php echo htmlspecialchars($alumno['boleta']); ?>">
          </div>

          <div class="col-12 col-md-4">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                   value="<?php echo htmlspecialchars($alumno['nombre']); ?>">
          </div>

          <div class="col-12 col-md-4">
            <label for="apellido_p">Apellido Paterno</label>
            <input type="text" id="apellido_p" name="apellido_p" class="form-control"
                   value="<?php echo htmlspecialchars($alumno['apellido_p']); ?>">
          </div>

          <div class="col-12 col-md-4">
            <label for="apellido_m">Apellido Materno</label>
            <input type="text" id="apellido_m" name="apellido_m" class="form-control"
                   value="<?php echo htmlspecialchars($alumno['apellido_m'] ?? ''); ?>">
          </div>

        </div>

        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id_alumno" value="<?php echo $id_alumno; ?>">

        <div class="mt-3">
          <button type="submit" id="btn-crear">
            <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
          </button>
          <a href="./AlumnosView.php" class="btn btn-secondary ms-2">Cancelar</a>
        </div>

      </form>
    </div>
  </div>

</body>
</html>