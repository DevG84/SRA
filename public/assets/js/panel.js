$(document).ready(() => {

    const idCoordinador = $('#main-content').data('coordinador');
    let examenesData = [];

    function cargarExamenes() {
        $.ajax({
            url: '/public/controller/ExamenController.php',
            method: 'GET',
            data: { coordinador: idCoordinador },
            dataType: 'json',
            success: (examenes) => {
                examenesData = examenes;
                renderExamenes(examenes);
            },
            error: () => {
                $('#tbody-panel').html('<tr><td colspan="8" class="texto-center">Error al cargar los exámenes</td></tr>');
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
