$(document).ready(() => {

    const id_alumno = document.getElementById("data-id_alumno").value;
    let disponiblesData = [];

    // EXAMENES INSCRITOS

    function cargarInscritos() {
        $.ajax({
            url: "../controller/InscripcionController.php",
            method: 'GET',
            data: { id_alumno: id_alumno, tipo: 'inscritos' },
            dataType: 'json',
            success: (examenes) => {
                const tbody = document.getElementById("tbody-inscritos");

                if (examenes.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="texto-center">El alumno no está registrado en ningún examen</td></tr>';
                    return;
                }

                tbody.innerHTML = examenes.map(e => `
                    <tr>
                        <td>${e.materia}</td>
                        <td>${e.carrera}</td>
                        <td>${new Date(e.fecha + "T00:00:00").toLocaleDateString("es-MX")}</td>
                        <td>${e.turno}</td>
                        <td>${e.edificio}${e.salon}</td>
                        <td>
                            <button class="btn-eliminar" onclick="desinscribir(${e.id_examen})">
                                <i class="fa-solid fa-xmark"></i> Quitar
                            </button>
                        </td>
                    </tr>
                `).join("");
            },
            error: () => console.error("Error al cargar exámenes inscritos")
        });
    }

    // EXAMENES DISPONIBLES

    function cargarDisponibles() {
        $.ajax({
            url: "../controller/InscripcionController.php",
            method: 'GET',
            data: { id_alumno: id_alumno, tipo: 'disponibles' },
            dataType: 'json',
            success: (examenes) => {
                disponiblesData = examenes;
                renderDisponibles(examenes);
            },
            error: () => console.error("Error al cargar exámenes disponibles")
        });
    }

    function renderDisponibles(examenes) {
        const tbody = document.getElementById("tbody-disponibles");

        if (examenes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="texto-center">No hay exámenes disponibles</td></tr>';
            return;
        }

        tbody.innerHTML = examenes.map(e => `
            <tr>
                <td>${e.materia}</td>
                <td>${e.carrera}</td>
                <td>${new Date(e.fecha + "T00:00:00").toLocaleDateString("es-MX")}</td>
                <td>${e.turno}</td>
                <td>${e.edificio}${e.salon}</td>
                <td>
                    <button class="btn-accion" onclick="inscribir(${e.id_examen})">
                        <i class="fa-solid fa-plus"></i> Registrar
                    </button>
                </td>
            </tr>
        `).join("");
    }

    // Filtro de disponibles
    document.getElementById("filtro-disponibles-texto")
        .addEventListener("input", function() {
            const texto = this.value.toLowerCase();
            const filtrados = disponiblesData.filter(e =>
                e.materia.toLowerCase().includes(texto) ||
                e.carrera.toLowerCase().includes(texto)
            );
            renderDisponibles(filtrados);
        });

    // INSCRIBIR O QUITAR INSCRIPCION

    window.inscribir = function(id_examen) {
        $.ajax({
            url: "../controller/InscripcionController.php",
            method: "POST",
            data: { accion: "inscribir", id_alumno: id_alumno, id_examen: id_examen },
            dataType: "json",
            success: (respuesta) => {
                Swal.fire({
                    title: "SRA",
                    text: respuesta.msj,
                    icon: respuesta.icono,
                    didDestroy: () => {
                        if (respuesta.cod) {
                            cargarInscritos();
                            cargarDisponibles();
                        }
                    }
                });
            },
            error: () => Swal.fire({ title: "Error", text: "No se pudo conectar", icon: "error" })
        });
    }

    window.desinscribir = function(id_examen) {
        Swal.fire({
            title: "¿Quitar registro?",
            text: "El alumno dejará de estar inscrito a este examen",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, quitar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/InscripcionController.php",
                    method: "POST",
                    data: { accion: "desinscribir", id_alumno: id_alumno, id_examen: id_examen },
                    dataType: "json",
                    success: (respuesta) => {
                        Swal.fire({
                            title: "SRA",
                            text: respuesta.msj,
                            icon: respuesta.icono,
                            didDestroy: () => {
                                if (respuesta.cod) {
                                    cargarInscritos();
                                    cargarDisponibles();
                                }
                            }
                        });
                    },
                    error: () => Swal.fire({ title: "Error", text: "No se pudo conectar", icon: "error" })
                });
            }
        });
    }

    // Inicio
    cargarInscritos();
    cargarDisponibles();

});