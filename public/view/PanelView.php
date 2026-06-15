<?php
session_start();

require_once __DIR__ . '/../../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control</title>
    <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/theme.scss">
    <link rel="stylesheet" href="../assets/css/panel.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />

    <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
    
    <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
    
    <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>

    <script defer src="../assets/js/panel.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body class="d-flex min-vh-100"> 
    <?php 
        $paginaActiva = 'panel';
        include __DIR__ . '/../partials/sidebar.php'; 
    ?>

    <main class="flex-grow-1 p-4" style="overflow-x: hidden;">
        <h1 class="h1">Panel de control</h1>
        <h4 class="h4 mt-3">Buscar</h4>
        <div class="contenedor-controles">
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
                    <input class="form-control"
                        type="text"
                        id="input-busqueda"
                        placeholder="Buscar por materia, carrera o coordinador"
                        autocomplete="off"
                    >
                    </div>
                    <div class="d-grid col-12 col-md-3">
                        <button type="button" class="btn btn-primary" id="btn-buscar">
                            <span class="material-symbols-outlined btn-icon">search</span>
                            Buscar
                        </button>
                    </div>
                </div>

                </section>
        </div>
        <h4 class="h4 mt-3">Otros controles</h4>
        <div class="contenedor-controles-2 mt-1 mb-3">
            <section class="seccion-botones d-flex justify-content-start">
                <button onclick="window.location.href='./CrearExamenView.php';" type="button" class="btn btn-warning" id="btn-crear">
                    <span class="material-symbols-outlined btn-icon">add</span>
                    Crear nuevo examen
                </button>
            </section>
        </div>

        <div class="materias-control d-flex justify-content-between align-items-center mb-3">
            <h5 class="t2 h5 m-0">Tus materias:</h5>
            
            <div id="btnsVarios">
                <button type="button" class="btn btn-secondary" id="btn-eliminar-varios">
                    <span class="material-symbols-outlined btn-icon">delete</span>
                    Eliminar
                </button>
            </div>
        </div>

        <section class="row g-3" id="contenedor-tarjetas">
            <div class="" id="contenedor-examenes"></div>
        </section>
    </main>

</body>
</html>
</html>