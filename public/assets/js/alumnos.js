$(document).ready(() => {

    let alumnosData = [];

    function cargarTabla() {
        $.ajax({
            url: "../controller/AlumnoController.php",
            method: 'GET',
            dataType: 'json',
            success: (alumnos) => {
                alumnosData = alumnos;
                renderAlumnos(alumnos);
            },
            error: () => console.error("Error al cargar alumnos")
        });
    }

    function renderAlumnos(alumnos) {
        const tbody = document.getElementById("tbody-alumnos");

        if (alumnos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="texto-center">No hay alumnos registrados</td></tr>';
            return;
        }

        tbody.innerHTML = alumnos.map(a => `
            <tr>
                <td hidden>${a.id_alumno}</td>
                <td>${a.boleta}</td>
                <td>${a.nombre} ${a.apellido_p} ${a.apellido_m ?? ''}</td>
                <td>
                    <a href="./EditarAlumnoView.php?id=${a.id_alumno}" class="btn-editar">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <button class="btn-eliminar" onclick="eliminarAlumno(${a.id_alumno})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <a href="./AlumnoExamenesView.php?id=${a.id_alumno}" class="btn-accion" style="padding:0.35rem 0.75rem; font-size:0.8rem;">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </a>
                </td>
            </tr>
        `).join("");
    }

    // Filtro por boleta o nombre
    document.getElementById("filtro-alumno-texto")
        .addEventListener("input", function() {
            const texto = this.value.toLowerCase();
            const filtrados = alumnosData.filter(a =>
                a.boleta.toLowerCase().includes(texto) ||
                (a.nombre + ' ' + a.apellido_p + ' ' + (a.apellido_m ?? '')).toLowerCase().includes(texto)
            );
            renderAlumnos(filtrados);
        });

    // Eliminar alumno
    window.eliminarAlumno = function(id) {
        Swal.fire({
            title: "¿Eliminar alumno?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/AlumnoController.php",
                    method: "POST",
                    data: { accion: "eliminar", id_alumno: id },
                    dataType: "json",
                    success: (respuesta) => {
                        Swal.fire({
                            title: "SRA",
                            text: respuesta.msj,
                            icon: respuesta.icono,
                            didDestroy: () => { if (respuesta.cod) cargarTabla(); }
                        });
                    },
                    error: () => Swal.fire({ title: "Error", text: "No se pudo conectar", icon: "error" })
                });
            }
        });
    }

    // Inicio
    cargarTabla();

});