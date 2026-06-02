<?php
// Vista pública — consulta de exámenes ETS
// No requiere sesión — accesible para cualquier usuario
?>
<!DOCTYPE html>
<html lang="es">
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

    <a href="./LoginView.php" id="btn-login">
      <i class="fa-solid fa-right-to-bracket"></i>
      <span>Acceso coordinadores</span>
    </a>
  </header>

    <!-- SECCIÓN DE FILTROS -->
    <section id="seccion-filtros">

      <!-- Fila de selects: Carrera, Semestre, Materia -->
      <div class="row g-3">

        <div class="col-12 col-md-4">
          <label for="filtro-carrera">Carrera</label>
          <select id="filtro-carrera" class="form-select">
            <option value="">Todas las carreras</option>
            <!-- Se llenará dinámicamente con fetch() al conectar el backend -->
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
            <!-- Se llenará dinámicamente según la carrera seleccionada -->
          </select>
        </div>

      </div>

      <hr class="separador">

      <!-- Búsqueda por texto -->
      <div class="row g-3 align-items-end">
        <div class="col-12 col-md-9">
          <label for="input-busqueda">Búsqueda por texto</label>
          <input
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

    <!-- CONTADOR DE RESULTADOS -->
    <p id="contador-resultados">
      Mostrando <span id="num-resultados">0</span> resultados
    </p>

    <!-- GRID DE CARDS -->
    <section id="grid-examenes">
      <div class="row g-3" id="contenedor-cards"></div>
      <div id="sin-resultados">
        <i class="fa-solid fa-calendar-xmark"></i>
        <p>No se encontraron exámenes con los filtros seleccionados.</p>
      </div>
    </section>

  <!-- Bootstrap JS -->
  <script src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.min.js"></script>

  <!-- JQuery -->
  <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>

  <!-- JQuery Examenes -->
  <script> src="../assets/js/libs/JQuery/Consulta.js" </script>

  <script>

    function filtrarExamenes(){

      const carrera = document.getElementById("filtro-carrera").value;
      const semestre = document.getElementById("filtro-semestre").value;
      const materia  = document.getElementById("filtro-materia").value;
      const coord    = document.getElementById("input-busqueda").value.trim();

      $.ajax({
        url: "../controller/ExamenController.php",
        method: 'GET',
        data: { carrera, semestre, materia, coord },
        dataType: 'json',
        success: (respuesta) => {
          cargarCards(respuesta);
        },
        error: (xhr) => {
          cargarCards([]);
        } 
      });

    }

    // Crear card de examen

    function crearCard(examen) {
      // Campos opcionales — muestra "Sin especificar" si viene vacío
      function campoOpcional(valor) {
        if (valor && valor.trim() !== "") {
          return `<span class="valor">${valor}</span>`;
        }
        return `<span class="valor vacio">Sin especificar</span>`;
      }

      return `
        <div class="col-12 col-md-6 col-lg-4">
          <article class="card-examen">

            <!-- Header azul: materia y carrera -->
            <div class="card-header-examen">
              <p class="materia">${examen.materia}</p>
              <p class="carrera">${examen.carrera}</p>
            </div>

            <!-- Body: detalles del examen -->
            <div class="card-body-examen">

              <div class="detalle-item">
                <i class="fa-regular fa-calendar icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Fecha</p>
                  <p class="valor">${examen.fecha}</p>
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-regular fa-clock icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Horario</p>
                  <p class="valor">${examen.horario}</p>
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-regular fa-user icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Coordinador</p>
                  <p class="valor">${examen.coordinador}</p>
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-regular fa-envelope icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Correo</p>
                  <p class="valor">${examen.correo}</p>
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-solid fa-location-dot icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Salón/Laboratorio</p>
                  <p class="valor">${examen.salon}</p>
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-regular fa-file-code icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Proyecto</p>
                  ${campoOpcional(examen.proyecto)}
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-regular fa-file-lines icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Guía</p>
                  ${campoOpcional(examen.guia)}
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-solid fa-circle-info icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Nota</p>
                  ${campoOpcional(examen.nota)}
                </div>
              </div>

            </div>
          </article>
        </div>
      `;
    }

    // CArgar cards de Examenes

    function cargarCards(lista) {
      const contenedor    = document.getElementById("contenedor-cards");
      const sinResultados = document.getElementById("sin-resultados");
      const numResultados = document.getElementById("num-resultados");

      numResultados.textContent = lista.length;

      if (lista.length === 0) {
        contenedor.innerHTML = "";
        sinResultados.style.display = "block";
      } else {
        sinResultados.style.display = "none";
        contenedor.innerHTML = lista.map(crearCard).join("");
      }
    }

    // EVENTOS
    document.getElementById("btn-buscar").addEventListener("click", filtrarExamenes);

    document.getElementById("input-busqueda").addEventListener("keydown", e => {
        if (e.key === "Enter") filtrarExamenes();
      });

    ["filtro-carrera", "filtro-semestre", "filtro-materia"].forEach(id => {
      document.getElementById(id).addEventListener("change", filtrarExamenes);
    });

    // INICIO — cargar todas las cards al abrir la página
    filtrarExamenes();
  </script>

</body>
</html>