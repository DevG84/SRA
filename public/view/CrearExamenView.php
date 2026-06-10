<?php
    //AGREGAR SESION
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__ . '/../../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <!-- Para mejorar la accesibilidad y responsivo -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Examen — SRA ESCOM</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.min.css">

  <!-- Font Awesome-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Estilos de la vista -->
  <link rel="stylesheet" href="../assets/css/crearExamen.css">
</head>

<body>

  <header id="header">
    <div class="logo">
        <img src="../assets/img/logo_ipn.png" alt="Logo ESCOM">
    </div>

    <h1>Sistema de Consulta ETS</h1>
    
    <div class="logo">
        <img src="../assets/img/logo_escom_blanco.png" alt="Logo ESCOM">
    </div>

  </header>

  <main style="padding: 0 0.75rem;">
    <div id="contenedor-form">
    <h2><i class="fa-solid fa-calendar-plus"></i> Crear Examen ETS</h2>
    <form id="form-crear-examen" autocomplete="off" enctype="multipart/form-data">

        <div class="row g-3">

            <!-- Carrera -->
            <div class="col-12 col-md-4">
            <label   label for="id_carrera">Carrera</label>
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
                    <!-- Se llena dinámicamente -->
                </select>
            </div>

            <!-- Fecha -->
            <div class="col-12 col-md-4">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" class="form-control">
            </div>

            <!-- Turno -->
            <div class="col-12 col-md-4">
                <label for="turno">Turno</label>
                <select id="turno" name="turno" class="form-select">
                    <option value="">Seleccionar Turno</option>
                    <option value="Matutino">Matutino</option>
                    <option value="Vespertino">Vespertino</option>
                </select>
            </div>

            <!-- Horario -->
            <div class="col-12 col-md-6">
                <label for="horario">Horario</label>
                <input type="text" id="horario" name="horario" class="form-control" placeholder="ej. 8:00 a 10:00">
            </div>

            <!-- Nota (opcional) -->
            <div class="col-12 col-md-6">
                <label for="nota">Nota <span class="text-muted">(opcional)</span></label>
                <input type="text" id="nota" name="nota" class="form-control">
            </div>

            <!-- Guía (opcional) -->
            <div class="col-12 col-md-6">
                <label for="guia">Guía <span class="text-muted">(opcional, PDF)</span></label>
                <input type="file" id="guia" name="guia" class="form-control" accept=".pdf">
            </div>

            <!-- Proyecto (opcional) -->
            <div class="col-12 col-md-6">
                <label for="proyecto">Proyecto <span class="text-muted">(opcional, PDF)</span></label>
                <input type="file" id="proyecto" name="proyecto" class="form-control" accept=".pdf">
            </div>

    </div>

    <!-- Campo oculto: coordinador de la sesión -->
    <input type="hidden" id="id_coordinador" name="id_coordinador" 
            value="<?php echo $_SESSION['id_coordinador'] ?? ''; ?>">

    <!-- Campo oculto: acción para el controller -->
    <input type="hidden" name="accion" value="agregar">

    <button type="submit" id="btn-crear">
        <i class="fa-solid fa-plus"></i> Crear examen
    </button>

    </form>
    </div>
    </main>

    <!-- JQuery -->
    <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>

    <!-- SweetAlert JS -->
    <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>

    <!-- JustValidate -->
    <script src="../assets/js/libs/JustValidate/justValidate.min.js"></script>

    <!-- JQuery Examenes -->
    <script defer src="../assets/js/crearExamen.js"></script>
</body>