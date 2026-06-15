$(document).ready(() => {

    const idCoordinador = $('#main-content').data('coordinador');
    let examenesData = [];

function crearTarjeta(examen, gestion) {
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

    let apellidoM = (examen.coordApellidoM != null) ? examen.coordApellidoM : '';

    let dataCoordinador = (gestion) ? `
    <span><i class="fa-solid fa-location-dot me-1"></i>
    Coordinador: ${examen.coordNombre} ${examen.coordApellidoP} ${apellidoM}
    </span>` : '';

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
                        ${dataCoordinador}
                        <span><i class="fa-regular fa-calendar me-1"></i> ${fechaFormateada}</span>
                        <span><i class="fa-regular fa-clock me-1"></i> ${examen.horario} hrs</span>
                        <span><i class="fa-solid fa-location-dot me-1"></i> Salón: ${examen.edificio}${examen.salon}</span>
                    </div>
                </div>
            </div>

            <div class="acciones-examen d-flex gap-2 justify-content-end align-self-end align-self-md-center w-100 w-md-auto">
              <button type="button" class="btn btn-outline-primary d-flex align-items-center justify-content-center btn-editar-examen p-2" 
               data-id="${examen.id_examen}" title="Editar Examen">
                <span class="material-symbols-outlined">edit</span>
              </button>
              <button type="button" class="btn btn-outline-danger d-flex align-items-center justify-content-center btn-eliminar-examen p-2" 
               data-id="${examen.id_examen}" data-materia="${examen.materia}" data-carrera="${examen.carrera}" title="Eliminar Examen">
                <span class="material-symbols-outlined">delete</span>
              </button>
            </div>

          </article>
        </div>
    `;
}

function accionesTarjetas() {
    const cantidadSeleccionados = $('.chk-seleccionar-examen:checked').length;
    const btnsVarios = $('#btnsVarios');
    const btnVarios = $('#btn-eliminar-varios');

    if (cantidadSeleccionados > 0) {
        btnVarios.removeClass('deshabilitado').prop('disabled', false);
        btnsVarios.show();
    } else {
        btnVarios.addClass('deshabilitado').prop('disabled', true);
        btnsVarios.hide();
    }
}

function esGestion() {
    return $.ajax({
        url: "../controller/CoordinadorController.php",
        method: 'GET',
        data: { verificar: 1 },
        dataType: 'json'
    })
    .then((respuesta) => {
        return respuesta.es_gestion === true; 
    })
    .catch(() => {
        return false; 
    });
}


async function renderizarTarjetas(listaExamenes) {
    const contenedorRow = $('#contenedor-examenes'); 
    contenedorRow.empty();

    if (listaExamenes.length === 0) {
        contenedorRow.html('<div class="col-12 text-center py-4"><p class="text-muted">No se encontraron exámenes asignados.</p></div>');
        accionesTarjetas();
        return;
    }

    const esGestionUsuario = await esGestion();

    listaExamenes.forEach(examen => {
        const tarjetaHTML = crearTarjeta(examen, esGestionUsuario);
        contenedorRow.append(tarjetaHTML);
    });

    accionesTarjetas();
}

function filtrarExamenes() {
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

function eliminarVarios(examenes) {
    $.ajax({
        url: "../controller/ExamenController.php",
        method: 'POST',
        data: { 
            accion: 'eliminar-varios',
            ids: examenes 
        },
        dataType: 'json',
        success: (respuesta) => {
            if (respuesta.cod === 1) { 
                Swal.fire({
                    title: '¡Eliminados!',
                    text: respuesta.msj,
                    icon: respuesta.icono,
                    background: '#212529',
                    color: '#fff'
                });
                
                filtrarExamenes();
            } else {
                Swal.fire({
                    title: 'Error',
                    text: respuesta.msj,
                    icon: respuesta.icono,
                    background: '#212529',
                    color: '#fff'
                });
            }
        });
    }

    function renderExamenes(examenes) {
        const tbody = document.getElementById('tbody-panel');

        if (examenes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="texto-center">No tienes exámenes registrados</td></tr>';
            return;
        }

        tbody.innerHTML = examenes.map(e => `
            <tr>
                <td hidden>${e.id_examen}</td>
                <td>${e.materia}</td>
                <td>${e.carrera}</td>
                <td>${new Date(e.fecha + 'T00:00:00').toLocaleDateString('es-MX')}</td>
                <td>${e.turno}</td>
                <td>${e.horario}</td>
                <td>Edif. ${e.edificio} — ${e.salon}</td>
                <td>
                    <a href="./EditarExamenView.php?id=${e.id_examen}" class="btn-editar">
                        <i class="fa-solid fa-pen"></i> Editar
                    </a>
                    <button class="btn-eliminar" onclick="eliminarExamen(${e.id_examen})">
                        <i class="fa-solid fa-trash"></i> Eliminar
                    </button>
                </td>
            </tr>
        `).join('');
    }

    $('#filtro-panel-texto').on('input', function () {
        const texto = this.value.toLowerCase();
        const filtrados = examenesData.filter(e =>
            e.materia.toLowerCase().includes(texto) ||
            e.carrera.toLowerCase().includes(texto)
        );
        renderExamenes(filtrados);
    });

    window.eliminarExamen = function (id_examen) {
        Swal.fire({
            title: '¿Eliminar examen?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '/public/controller/ExamenController.php',
                method: 'POST',
                data: { accion: 'eliminar', id_examen: id_examen },
                dataType: 'json',
                success: (respuesta) => {
                    Swal.fire({
                        title: respuesta.msj,
                        icon: respuesta.icono,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => cargarExamenes());
                },
                error: () => Swal.fire('Error', 'No se pudo eliminar el examen', 'error')
            });
        });
    };

    cargarExamenes();

});
