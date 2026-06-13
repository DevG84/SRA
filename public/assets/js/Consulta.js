//Cargar Carreras en el select

function cargarCarreras(){
    $.ajax({
        url: "../controller/CarreraController.php",
        method: 'GET',
        dataType: 'json',
        success: (carreras) => {
            const select = document.getElementById("filtro-carrera");
            carreras.forEach(carrera => {
                const option = document.createElement("option");
                option.value = carrera.id_carrera;
                option.textContent = carrera.nombre;
                select.appendChild(option);
            });
        },
        error: () => {
            
        } 
    });
}

// Cargar Materias en el select

function cargarMaterias(idCarrera) {

    //Limpiar el select de materias antes de cargar nuevas opciones
    const select = document.getElementById("filtro-materia");
    select.innerHTML = '<option value="">Todas las materias</option>';
    

    $.ajax({
        url: "../controller/MateriaController.php",
        method: 'GET',
        data: { carrera: idCarrera }, // Enviar el ID de la carrera para filtrar materias
        dataType: 'json',
        success: (materias) => {
            const select = document.getElementById("filtro-materia");
            materias.forEach(materia => {
                const option = document.createElement("option");
                option.value = materia.id_materia;
                option.textContent = materia.nombre;
                select.appendChild(option);
            });
        },
        error: () => {
            
        } 
    });
}


// Llamar exmanenes filtrados
function filtrarExamenes(){

      const carrera = document.getElementById("filtro-carrera").value;
      const semestre = document.getElementById("filtro-semestre").value;
      const materia  = document.getElementById("filtro-materia").value;
      const texto    = document.getElementById("input-busqueda").value.trim();

      $.ajax({
        url: "../controller/ExamenController.php",
        method: 'GET',
        cache: false,
        data: { carrera, semestre, materia, texto, origen: 'publico'}, // Enviar filtros al controlador
        dataType: 'json',
        success: (respuesta) => {
          cargarCards(respuesta);
        },
        error: () => {
          cargarCards([]);
        } 
      });

    }

    // Crear card de examen

    function crearCard(examen) {

      return `
        <div class="col-12 col-md-6 col-lg-4">
          <article class="card-examen">

            <!-- Header: materia y carrera -->
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
                  <p class="valor">${new Date(examen.fecha + "T00:00:00").toLocaleDateString("es-MX")}</p>
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
                  <p class="valor">${examen.coordNombre} ${examen.coordApellidoP} ${examen.coordApellidoM}</p>
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-regular fa-envelope icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Correo</p>
                  <p class="valor">${examen.coordCorreo}</p>
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
                  ${examen.proyecto
                      ? `<a href="../${examen.proyecto}" target="_blank" class="valor enlace">Ver proyecto</a>`
                      : `<span class="valor vacio">Sin especificar</span>`
                  }
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-regular fa-file-lines icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Guía</p>
                  ${examen.guia
                      ? `<a href="../${examen.guia}" target="_blank" class="valor enlace">Ver guía</a>`
                      : `<span class="valor vacio">Sin especificar</span>`
                  }
                </div>
              </div>

              <div class="detalle-item">
                <i class="fa-solid fa-circle-info icono"></i>
                <div class="contenido">
                  <p class="etiqueta">Nota</p>
                  ${examen.nota ? `<span class="valor">${examen.nota}</span>` : `<span class="valor vacio">Sin especificar</span>`}
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

      if (numResultados) {
        numResultados.textContent = lista.length;
      }

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

    document.getElementById("filtro-carrera").addEventListener("change", function() {
      cargarMaterias(this.value); // Cargar materias según la carrera seleccionada
      filtrarExamenes(); // Refrescar resultados al cambiar de carrera
    });

    // INICIO — cargar lo necesario al cargar la página
    cargarCarreras();
    cargarMaterias(''); // Cargar todas las materias inicialmente
    filtrarExamenes();
