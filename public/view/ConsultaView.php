<?php
// Vista pública — consulta de exámenes ETS
// No requiere sesión — accesible para cualquier usuario

  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <!-- Para mejorar la accesibilidad y responsivo -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consulta ETS — SRA ESCOM</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.min.css">

  <!-- Font Awesome-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Estilos de la vista -->
  <link rel="stylesheet" href="../assets/css/consulta.css">
</head>


<body>

  <!-- HEADER - Contiene: logo, título, botón acceso coordinadores -->
  <header id="header">
    <div class="logo">
        <img src="../assets/img/logo_ipn.png" alt="Logo ESCOM">
    </div>

    <div class="logo">
        <img src="../assets/img/logo_escom_blanco.png" alt="Logo ESCOM">
    </div>

    <h1>Sistema de Consulta ETS</h1>

    <?php if (isset($_SESSION['id_coordinador'])): ?>
      <!-- Ya hay sesión — ir al dashboard correspondiente -->
      <a href="<?php echo $_SESSION['rol'] === 'gestion' ? './DashboardGestionView.php' : './DashboardCoordinadoresView.php'; ?>" id="btn-login">
          <span>Dashboard</span>
      </a>
    <?php else: ?>
        <!-- Sin sesión — ir al login -->
        <a href="./login.php" id="btn-login">
            <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
            <span>Acceso</span>
        </a>
    <?php endif; ?>
  </header>

    <!-- SECCIÓN DE FILTROS -->
    <section id="seccion-filtros">

      <!-- Fila de selects: Carrera, Semestre, Materia -->
      <div class="row g-3">

        <div class="col-12 col-md-4">
          <label for="filtro-carrera">Carrera</label>
          <select id="filtro-carrera" class="form-select">
            <option value="">Todas las carreras</option>
            <!-- Se llena dinámicamente -->
          </select>
        </div>

        <div class="col-12 col-md-4">
          <label for="filtro-semestre">Semestre</label>
          <select id="filtro-semestre" class="form-select">
            <option value="">Todos los semestres</option>
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

        <div class="col-12 col-md-4">
          <label for="filtro-materia">Materia</label>
          <select id="filtro-materia" class="form-select">
            <option value="">Todas las materias</option>
            <!-- Se llena dinámicamente -->
          </select>
        </div>

      </div>

      <hr class="separador">

      <!-- Búsqueda por texto -->
      <div class="row g-3 align-items-end">
        <div class="col-12 col-md-9">
          <label for="input-busqueda">Búsqueda por texto</label>
          <input class="input"
            type="text"
            id="input-busqueda"
            placeholder="Buscar por materia, carrera o coordinador"
            autocomplete="off"
          >
        </div>
        <div class="col-12 col-md-3">
          <button id="btn-buscar" type="button">
            <i class="fa-solid fa-magnifying-glass"></i>
            Buscar
          </button>
        </div>
      </div>

    </section>

    <!-- GRID DE CARDS -->
    <section id="grid-examenes">
      <div class="row g-3" id="contenedor-cards"></div>
      <div id="sin-resultados">
        <i class="fa-solid fa-calendar-xmark"></i>
        <p>No se encontraron exámenes con los filtros seleccionados.</p>
      </div>
    </section>

    <footer>
        <div id="footer">
        <div class="container">
            <p class="text-muted credit">© 2026 SRA Alumnos de ESCOM</p>
        </div>
        </div>
    </footer>

  <!-- Bootstrap JS -->
  <script src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.min.js"></script>

  <!-- JQuery -->
  <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>

  <!-- JQuery Examenes -->
  <script src="../assets/js/Consulta.js" ></script>

  <footer>
        <div id="footer">
        <div class="container">
            <p class="text-muted credit">© 2026 SRA Alumnos de ESCOM</p>
        </div>
        </div>
    </footer>

</body>
</html>