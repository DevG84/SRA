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

  <!-- ================================================
       HEADER
       Contiene: logo, título, botón acceso coordinadores
  ================================================= -->
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

    <!-- ============================================
         SECCIÓN DE FILTROS
    ============================================= -->
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

      <!-- Búsqueda por texto + botón -->
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

    <!-- ============================================
         CONTADOR DE RESULTADOS
    ============================================= -->
    <p id="contador-resultados">
      Mostrando <span id="num-resultados">0</span> resultados
    </p>

    <!-- ============================================
         GRID DE CARDS
    ============================================= -->
    <section id="grid-examenes">
      <div class="row g-3" id="contenedor-cards"></div>
      <div id="sin-resultados">
        <i class="fa-solid fa-calendar-xmark"></i>
        <p>No se encontraron exámenes con los filtros seleccionados.</p>
      </div>
    </section>

  <!-- Bootstrap JS -->
  <script src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.min.js"></script>

  <script>
    // ================================================
    // DATOS DE PRUEBA (hardcodeados)
    // TODO: reemplazar con fetch() al endpoint PHP
    // GET /api/examenes?carrera=&semestre=&materia=
    // ================================================
    const examenes = [
      {
        id: 1,
        materia: "Administración de Proyectos",
        carrera: "ISC - 2009",
        fecha: "10 de feb de 2026",
        horario: "8:00 a 10:00 y 14:00 a 16:00",
        coordinador: "Verónica Agustín Domínguez",
        correo: "vagustin@ipn.mx",
        salon: "2106",
        nota: "",
        guia: "",
        proyecto: ""
      },
      {
        id: 2,
        materia: "Estructuras de Datos",
        carrera: "ISC - 2020",
        fecha: "11 de feb de 2026",
        horario: "8:00 a 10:00",
        coordinador: "José Antonio Ortiz Ramírez",
        correo: "jortiz@ipn.mx",
        salon: "2010",
        nota: "Revisar tema 5",
        guia: "",
        proyecto: ""
      },
      {
        id: 3,
        materia: "Bases de Datos",
        carrera: "ISC - 2020",
        fecha: "12 de feb de 2026",
        horario: "16:00 a 18:00",
        coordinador: "María Elena González López",
        correo: "mgonzalez@ipn.mx",
        salon: "Lab 3005",
        nota: "",
        guia: "Guía en SAPD",
        proyecto: ""
      },
      {
        id: 4,
        materia: "Cálculo Diferencial",
        carrera: "IIA - 2020",
        fecha: "13 de feb de 2026",
        horario: "10:00 a 12:00",
        coordinador: "María Elena González López",
        correo: "mgonzalez@ipn.mx",
        salon: "1002",
        nota: "",
        guia: "",
        proyecto: ""
      },
      {
        id: 5,
        materia: "Introducción a la IA",
        carrera: "IIA - 2020",
        fecha: "14 de feb de 2026",
        horario: "14:00 a 16:00",
        coordinador: "Verónica Agustín Domínguez",
        correo: "vagustin@ipn.mx",
        salon: "2106",
        nota: "",
        guia: "",
        proyecto: "Proyecto en GitHub"
      },
      {
        id: 6,
        materia: "Redes de Computadoras",
        carrera: "ISC - 2009",
        fecha: "15 de feb de 2026",
        horario: "8:00 a 10:00",
        coordinador: "José Antonio Ortiz Ramírez",
        correo: "jortiz@ipn.mx",
        salon: "2010",
        nota: "",
        guia: "Guía en SAPD",
        proyecto: ""
      }
    ];

    // ================================================
    // FUNCIÓN: Crear HTML de una card de examen
    // ================================================
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

    // ================================================
    // FUNCIÓN: Renderizar cards en el DOM
    // ================================================
    function renderizarCards(lista) {
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

    // ================================================
    // FUNCIÓN: Filtrar exámenes con los valores actuales
    // ================================================
    function filtrarExamenes() {
      const carrera  = document.getElementById("filtro-carrera").value.toLowerCase();
      const semestre = document.getElementById("filtro-semestre").value;
      const materia  = document.getElementById("filtro-materia").value.toLowerCase();
      const texto    = document.getElementById("input-busqueda").value.toLowerCase().trim();

      const resultado = examenes.filter(examen => {
        const coincideCarrera = !carrera  || examen.carrera.toLowerCase().includes(carrera);
        const coincideMateria = !materia  || examen.materia.toLowerCase().includes(materia);
        const coincideTexto   = !texto    ||
          examen.materia.toLowerCase().includes(texto)      ||
          examen.carrera.toLowerCase().includes(texto)      ||
          examen.coordinador.toLowerCase().includes(texto);

        return coincideCarrera && coincideMateria && coincideTexto;
      });

      renderizarCards(resultado);
    }

    // ================================================
    // EVENTOS
    // ================================================
    document.getElementById("btn-buscar")
      .addEventListener("click", filtrarExamenes);

    document.getElementById("input-busqueda")
      .addEventListener("keydown", e => {
        if (e.key === "Enter") filtrarExamenes();
      });

    ["filtro-carrera", "filtro-semestre", "filtro-materia"].forEach(id => {
      document.getElementById(id).addEventListener("change", filtrarExamenes);
    });

    // ================================================
    // INICIO — cargar todas las cards al abrir la página
    // ================================================
    renderizarCards(examenes);
  </script>

</body>
</html>