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

// Cargar departamentos para el select
require_once __DIR__ . '/../../includes/db.php';
$conn = (new Connection)->connect();
$stmt = $conn->prepare("SELECT id_departamento, nombre FROM departamento ORDER BY nombre ASC");
$stmt->execute();
$departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Coordinador — SRA ESCOM</title>
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
  <script defer src="../assets/js/crearCoordinador.js"></script>
</head>
<body>

  <?php
  $paginaActiva = 'coordinadores';
  include __DIR__ . '/partials/sidebar.php';
  ?>

  <div id="main-content">
    <div id="contenedor-form">
      <h2><i class="fa-solid fa-user-plus"></i> Registrar Coordinador</h2>

      <form id="form-crear-coordinador" autocomplete="off">

        <div class="row g-3">

          <div class="col-12 col-md-4">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre(s)">
          </div>

          <div class="col-12 col-md-4">
            <label for="apellido_p">Apellido Paterno</label>
            <input type="text" id="apellido_p" name="apellido_p" class="form-control">
          </div>

          <div class="col-12 col-md-4">
            <label for="apellido_m">Apellido Materno</label>
            <input type="text" id="apellido_m" name="apellido_m" class="form-control">
          </div>

          <div class="col-12 col-md-6">
            <label for="id_departamento">Departamento</label>
            <select id="id_departamento" name="id_departamento" class="form-select">
              <option value="">Seleccionar Departamento</option>
              <?php foreach ($departamentos as $dep): ?>
                <option value="<?php echo $dep['id_departamento']; ?>">
                  <?php echo htmlspecialchars($dep['nombre']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 col-md-6">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" class="form-control" placeholder="correo@ipn.mx">
          </div>

          <div class="col-12 col-md-6">
            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" class="form-control">
          </div>

          <div class="col-12 col-md-6">
            <label for="confirmar_contrasena">Confirmar Contraseña</label>
            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" class="form-control">
          </div>

        </div>

        <input type="hidden" name="accion" value="agregar">

        <div class="mt-3">
          <button type="submit" id="btn-crear">
            <i class="fa-solid fa-plus"></i> Registrar coordinador
          </button>
          <a href="./CoordinadoresView.php" class="btn btn-secondary ms-2">Cancelar</a>
        </div>

      </form>
    </div>
  </div>

</body>
</html>