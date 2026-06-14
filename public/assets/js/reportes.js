$(document).ready(() => {

    let examenesData = [];

    // Cargar tabla de exámenes
    function cargarExamenes() {
        $.ajax({
            url: "../controller/ReporteController.php",
            method: 'GET',
            dataType: 'json',
            success: (examenes) => {
                examenesData = examenes;
                renderExamenes(examenes);
            },
            error: () => console.error("Error al cargar exámenes")
        });
    }

    function renderExamenes(examenes) {
        const tbody = document.getElementById("tbody-reportes");

        if (examenes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="texto-center">No hay exámenes registrados</td></tr>';
            return;
        }

        tbody.innerHTML = examenes.map(e => `
            <tr>
                <td hidden>${e.id_examen}</td>
                <td>${e.materia}</td>
                <td>${e.carrera}</td>
                <td>${new Date(e.fecha + "T00:00:00").toLocaleDateString("es-MX")}</td>
                <td>${e.turno}</td>
                <td>${e.edificio}${e.salon}</td>
                <td>${e.coordNombre} ${e.coordApellidoP}</td>
                <td>${e.total_alumnos}</td>
                <td>
                    <button class="btn-editar" onclick="verReporte(${e.id_examen}, '${e.materia}')">
                        <i class="fa-solid fa-eye"></i> Ver reporte
                    </button>
                </td>
            </tr>
        `).join("");
    }

    // Filtro por texto — busca en materia, carrera y coordinador
    document.getElementById("filtro-reporte-texto")
        .addEventListener("input", function() {
            const texto = this.value.toLowerCase();
            const filtrados = examenesData.filter(e =>
                e.materia.toLowerCase().includes(texto) ||
                e.carrera.toLowerCase().includes(texto) ||
                (e.coordNombre + ' ' + e.coordApellidoP).toLowerCase().includes(texto)
            );
            renderExamenes(filtrados);
        });

    // Abrir modal con alumnos inscritos
    window.verReporte = function(id_examen, materia) {
        document.getElementById("modal-titulo").textContent = "Alumnos registrados — " + materia;
        document.getElementById("btn-exportar-pdf").href = "../controller/ExportarReporteController.php?id_examen=" + id_examen;
        document.getElementById("tbody-alumnos").innerHTML =
            '<tr><td colspan="3" class="texto-center">Cargando...</td></tr>';

        const modal = new bootstrap.Modal(document.getElementById("modal-alumnos"));
        modal.show();

        $.ajax({
            url: "../controller/ReporteController.php",
            method: 'GET',
            data: { accion: 'alumnos', id_examen: id_examen },
            dataType: 'json',
            success: (alumnos) => {
                const tbody = document.getElementById("tbody-alumnos");

                if (alumnos.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="2" class="texto-center">No hay alumnos registrados para este examen</td></tr>';
                    return;
                }

                tbody.innerHTML = alumnos.map(a => `
                    <tr>
                        <td>${a.boleta}</td>
                        <td>${a.nombre} ${a.apellido_p} ${a.apellido_m ?? ''}</td>
                    </tr>
                `).join("");
            },
            error: () => {
                document.getElementById("tbody-alumnos").innerHTML =
                    '<tr><td colspan="3" class="texto-center">Error al cargar alumnos</td></tr>';
            }
        });
    }

    // Inicio
    cargarExamenes();

});