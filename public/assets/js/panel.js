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
        }
    });
}

function cargarMaterias(idCarrera) {
    const select = document.getElementById("filtro-materia");
    select.innerHTML = '<option value="">Todas las materias</option>';
    
    $.ajax({
        url: "../controller/MateriaController.php",
        method: 'GET',
        data: { carrera: idCarrera }, 
        dataType: 'json',
        success: (materias) => {
            materias.forEach(materia => {
                const option = document.createElement("option");
                option.value = materia.id_materia;
                option.textContent = materia.nombre;
                select.appendChild(option);
            });
        }
    });
}

function crearTarjeta(examen) {
    const fechaFormateada = new Date(examen.fecha + "T00:00:00").toLocaleDateString("es-MX", {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });

    const turno = examen.turno.toLowerCase() === 'matutino';
    const colorBadgeTurno = turno 
        ? 'text-info-emphasis bg-info-subtle border border-info-subtle' 
        : 'text-warning-emphasis bg-warning-subtle border border-warning-subtle';
        
    const alias = examen.carrera.slice(0, 3);
    let iconCarrera = "school";
    let estiloCarrera = "";
    let colorCarrera = "text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle";
    
    switch (alias) {
        case "ISC": 
            iconCarrera = "code_xml";
            colorCarrera = "bg-primary-subtle text-primary border-primary-subtle";
            estiloCarrera = "card-isc";
            break;
        case "IIA": 
            iconCarrera = "network_intel_node";
            colorCarrera = "bg-success-subtle text-success border-success-subtle";
            estiloCarrera = "card-iia";
            break;
        case "LCD": 
            iconCarrera = "graph_3";
            colorCarrera = "bg-warning-subtle text-warning-emphasis border-warning-subtle";
            estiloCarrera = "card-lcd";
            break;
    }

    return `
        <div class="col-12 mb-3">
          <article class="card p-3 border border-secondary-subtle shadow-sm flex-md-row align-items-md-center justify-content-between gap-3 transition-card estiloCarrera ${estiloCarrera}">   
            
            <div class="info-examen d-flex align-items-center gap-3">
                <div class="form-check m-0">
                    <input class="form-check-input chk-seleccionar-examen" type="checkbox" data-id="${examen.id_examen}">
                </div>
                
                <div>
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                        <span class="icono-carrera material-symbols-outlined">${iconCarrera}</span>
                        <h5 class="texto-materia card-title m-0 fw-semibold d-inline-block align-middle">${examen.materia}</h5>
                        <span class="badge border ${colorCarrera}">${examen.carrera}</span>
                        <span class="badge border ${colorBadgeTurno}">${examen.turno}</span>
                    </div>
                    
                    <div class="detalles d-flex flex-wrap gap-3 text-body-secondary">
                        <span><i class="fa-regular fa-calendar me-1"></i> ${fechaFormateada}</span>
                        <span><i class="fa-regular fa-clock me-1"></i> ${examen.horario} hrs</span>
                        <span><i class="fa-solid fa-location-dot me-1"></i> Salón: ${examen.edificio}${examen.salon}</span>
                    </div>
                </div>
            </div>

            <div class="acciones-examen d-flex gap-2 justify-content-end align-self-end align-self-md-center w-100 w-md-auto">
              <button type="button" class="btn btn-outline-primary d-flex align-items-center justify-content-center btn-editar-examen p-2" data-id="${examen.id_examen}" title="Editar Examen">
                <span class="material-symbols-outlined">edit</span>
              </button>
              <button type="button" class="btn btn-outline-danger d-flex align-items-center justify-content-center btn-eliminar-examen p-2" data-id="${examen.id_examen}" title="Eliminar Examen">
                <span class="material-symbols-outlined">delete</span>
              </button>
            </div>

          </article>
        </div>
    `;
}

function renderizarTarjetas(listaExamenes){
    const contenedorRow = $('#contenedor-examenes'); 
    contenedorRow.empty();

    if (listaExamenes.length === 0) {
        contenedorRow.html('<div class="col-12 text-center py-4"><p class="text-muted">No se encontraron exámenes asignados.</p></div>');
        return;
    }

    listaExamenes.forEach(examen => {
        const tarjetaHTML = crearTarjeta(examen);
        contenedorRow.append(tarjetaHTML);
    });
}

function filtrarExamenes(){
    const carrera  = document.getElementById("filtro-carrera").value;
    const semestre = document.getElementById("filtro-semestre").value;
    const materia  = document.getElementById("filtro-materia").value;
    const texto    = document.getElementById("input-busqueda").value.trim();

    $.ajax({
        url: "../controller/ExamenController.php",
        method: 'GET',
        cache: false,
        data: { carrera, semestre, materia, texto}, 
        dataType: 'json',
        success: (respuesta) => {
            renderizarTarjetas(respuesta);
        },
        error: () => {
            renderizarTarjetas([]);
        } 
    });
}

$(document).ready(function() {
    
    // Control lateral de interfaz
    $('#toggle-sidebar').on('click', function(e) {
        e.preventDefault();
        $('#sidebar').toggleClass('sidebar-collapsed');
    });

    // Cargas e inicializaciones prioritarias de datos
    cargarCarreras();
    cargarMaterias('');
    filtrarExamenes(); // Ejecuta la primera carga con filtros limpios

    // Escuchadores del buscador por texto
    $("#btn-buscar").on("click", filtrarExamenes);
    
    $("#input-busqueda").on("keydown", function(e) {
        if (e.key === "Enter") filtrarExamenes();
    });

    // Escuchadores rápidos para los combos Select comunes
    ["filtro-semestre", "filtro-materia"].forEach(id => {
        $(`#${id}`).on("change", filtrarExamenes);
    });

    // Escuchador jerárquico para Carrera (Actualiza dropdown de materias + recarga datos)
    $("#filtro-carrera").on("change", function() {
        cargarMaterias($(this).val()); 
        filtrarExamenes(); 
    });

    // Escuchador dinámico para selección múltiple (Checkboxes)
    $('#contenedor-examenes').on('change', '.chk-seleccionar-examen', function() {
        const checkbox = $(this);
        const tarjeta = checkbox.closest('article');

        const todosLosBtnEditar = $('#contenedor-examenes').find('.btn-editar-examen');
        const todosLosBtnEliminar = $('#contenedor-examenes').find('.btn-eliminar-examen');

        if (checkbox.is(':checked')) {
            tarjeta.addClass('card-seleccionada');
            todosLosBtnEditar.addClass('deshabilitado').prop('disabled', true);
            todosLosBtnEliminar.addClass('deshabilitado').prop('disabled', true);
        } else {
            tarjeta.removeClass('card-seleccionada');
            
            // Verificamos si aún queda algún otro checkbox activo en la página
            const checked = $('.chk-seleccionar-examen:checked').length;
            if (checked === 0) {
                todosLosBtnEditar.removeClass('deshabilitado').prop('disabled', false);
                todosLosBtnEliminar.removeClass('deshabilitado').prop('disabled', false);
            }
        }
    });

    // Escuchador para botones de acciones masivas compartidas
    $('#contenedor-examenes').on('click', '.btn-accion-masiva', function() {
        const idExamen = $(this).data('id');
        console.log("Procesando acción masiva para el examen ID:", idExamen);
    });
});