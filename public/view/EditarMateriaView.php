<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/auth.php';

if ($_SESSION['rol'] !== 'gestion') {
    header('Location: ./DashboardView.php');
    exit;
}

$id_materia = $_GET['id'] ?? null;

if (!$id_materia) {
    header('Location: ./GestionDashboardView.php');
    exit;
}

require_once __DIR__ . '/../../includes/db.php';
$conn = (new Connection)->connect();

// Cargar materia
$stmt = $conn->prepare("SELECT * FROM materia WHERE id_materia = :id");
$stmt->execute([':id' => $id_materia]);
$materia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$materia) {
    header('Location: ./GestionDashboardView.php');
    exit;
}

// Cargar carreras
$stmt = $conn->prepare("SELECT id_carrera, nombre, alias FROM carrera ORDER BY nombre ASC");
$stmt->execute();
$carreras = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cargar departamentos
$stmt = $conn->prepare("SELECT id_departamento, nombre FROM departamento ORDER BY nombre ASC");
$stmt->execute();
$departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Materia — SRA ESCOM</title>
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
  <script defer src="../assets/js/editarMateria.js"></script>
</head>
<body>

  <?php
  $paginaActiva = 'gestion';
  include __DIR__ . '/../partials/sidebar.php';
  ?>

  <div id="main-content">
    <div id="contenedor-form">
      <h2><i class="fa-solid fa-book-open"></i> Editar Materia</h2>

      <form id="form-editar-materia" autocomplete="off">

        <div class="row g-3">

          <div class="col-12 col-md-6">
            <label for="nombre">Nombre de la materia</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                   value="<?php echo htmlspecialchars($materia['nombre']); ?>">
          </div>

          <div class="col-12 col-md-3">
            <label for="id_carrera">Carrera</label>
            <select id="id_carrera" name="id_carrera" class="form-select">
              <option value="">Seleccionar Carrera</option>
              <?php foreach ($carreras as $c): ?>
                <option value="<?php echo $c['id_carrera']; ?>"
                  <?php echo $c['id_carrera'] == $materia['id_carrera'] ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($c['alias'] . ' — ' . $c['nombre']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 col-md-3">
            <label for="semestre">Semestre</label>
            <select id="semestre" name="semestre" class="form-select">
              <option value="">Seleccionar Semestre</option>
              <?php for ($i = 1; $i <= 8; $i++): ?>
                <option value="<?php echo $i; ?>"
                  <?php echo $i == $materia['semestre'] ? 'selected' : ''; ?>>
                  <?php echo $i; ?>° semestre
                </option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="col-12 col-md-6">
            <label for="id_departamento">Departamento</label>
            <select id="id_departamento" name="id_departamento" class="form-select">
              <option value="">Seleccionar Departamento</option>
              <?php foreach ($departamentos as $d): ?>
                <option value="<?php echo $d['id_departamento']; ?>"
                  <?php echo $d['id_departamento'] == $materia['id_departamento'] ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($d['nombre']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>

        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id_materia" value="<?php echo $id_materia; ?>">

        <div class="mt-3">
          <button type="submit" id="btn-crear">
            <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
          </button>
          <a href="./GestionDashboardView.php" class="btn btn-secondary ms-2">Cancelar</a>
        </div>

      </form>
    </div>
  </div>

</body>
</html>