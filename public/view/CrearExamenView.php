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
    <title>Crear Examen — SRA ESCOM</title>

    <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/crearExamen.css">

    <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
    <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
    <script src="../assets/js/libs/JustValidate/justValidate.min.js"></script>
    <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>
    <script defer src="../assets/js/crearExamen.js"></script>
</head>

<body>

    <?php
    $paginaActiva = 'crear';
    include __DIR__ . '/../partials/sidebar.php';
    ?>

    <!-- Contenido principal -->
    <main id="main-content">
        <a class="btn btn-dark btn-lg h3 back" href="./PanelView.php">
            <span class="material-symbols-outlined">arrow_back_ios</span>
            Regresar
        </a>

        <div id="contenedor-form">

            <h2 class="h2"><i class="fa-solid fa-calendar-plus"></i> Crear Examen ETS</h2>

            <form id="form-crear-examen" autocomplete="off" enctype="multipart/form-data">

                <div class="row g-3">

                    <div class="col-12 col-md-4">
                        <label for="id_carrera">Carrera</label>
                        <select id="id_carrera" name="id_carrera" class="form-select">
                            <option value="">Seleccionar Carrera</option>
                        </select>
                    </div>

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

                    <div class="col-12 col-md-4">
                        <label for="id_materia">Materia</label>
                        <select id="id_materia" name="id_materia" class="form-select">
                            <option value="">Seleccionar Materia</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="id_salon">Salón</label>
                        <select id="id_salon" name="id_salon" class="form-select">
                            <option value="">Seleccionar Salón</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" name="fecha" class="form-control">
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="turno">Turno</label>
                        <select id="turno" name="turno" class="form-select">
                            <option value="">Seleccionar Turno</option>
                            <option value="Matutino">Matutino</option>
                            <option value="Vespertino">Vespertino</option>
                        </select>
                    </div>

                    <?php if ($_SESSION['rol'] === 'gestion'): ?>
                        <!-- Gestión puede asignar a cualquier coordinador -->
                        <div class="col-12 col-md-6">
                            <label for="id_coordinador">Asignar a Coordinador</label>
                            <select id="id_coordinador" name="id_coordinador" class="form-select">
                                <option value="">Seleccionar Coordinador</option>
                            </select>
                        </div>
                    <?php else: ?>
                        <!-- Coordinador normal — su propio ID oculto -->
                        <input type="hidden" name="id_coordinador" value="<?php echo $_SESSION['id_coordinador']; ?>">
                    <?php endif; ?>

                    <div class="col-12 col-md-6">
                        <label for="horario">Horario</label>
                        <input type="text" id="horario" name="horario" class="form-control" placeholder="ej. 8:00 a 10:00">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="nota">Nota <span class="text-muted">(opcional)</span></label>
                        <input type="text" id="nota" name="nota" class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="guia">Guía <span class="text-muted">(opcional, PDF)</span></label>
                        <input type="file" id="guia" name="guia" class="form-control" accept=".pdf">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="proyecto">Proyecto <span class="text-muted">(opcional, PDF)</span></label>
                        <input type="file" id="proyecto" name="proyecto" class="form-control" accept=".pdf">
                    </div>

                </div>

                <input type="hidden" name="accion" value="agregar">

                <div class="mt-3">
                    <button type="submit" id="btn-crear">
                        <i class="fa-solid fa-plus"></i> Crear examen
                    </button>
                </div>

            </form>
        </div>
    </main>

</body>

</html>