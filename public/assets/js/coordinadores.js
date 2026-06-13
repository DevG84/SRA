$(document).ready(() => {

    // Cargar tabla de coordinadores
    function cargarTabla() {
        $.ajax({
            url: "../controller/CoordinadorController.php",
            method: 'GET',
            dataType: 'json',
            success: (coordinadores) => {
                const tbody = document.getElementById("tbody-coordinadores");

                if (coordinadores.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="texto-center">No hay coordinadores registrados</td></tr>';
                    return;
                }

                tbody.innerHTML = coordinadores.map(c => `
                    <tr>
                        <td hidden>${c.id_coordinador}</td>
                        <td>${c.nombre} ${c.apellido_p} ${c.apellido_m ?? ''}</td>
                        <td>${c.correo}</td>
                        <td>${c.departamento}</td>
                        <td>
                            <a href="./EditarCoordinadorView.php?id=${c.id_coordinador}" class="btn-editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button class="btn-eliminar" onclick="eliminarCoordinador(${c.id_coordinador})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join("");
            },
            error: () => {
                console.error("Error al cargar coordinadores");
            }
        });
    }

    // Eliminar coordinador
    function eliminarCoordinador(id) {
        Swal.fire({
            title: "¿Eliminar coordinador?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/CoordinadorController.php",
                    method: "POST",
                    data: { accion: "eliminar", id_coordinador: id },
                    dataType: "json",
                    success: (respuesta) => {
                        Swal.fire({
                            title: "SRA",
                            text: respuesta.msj,
                            icon: respuesta.icono,
                            didDestroy: () => {
                                if (respuesta.cod) cargarTabla();
                            }
                        });
                    },
                    error: () => {
                        Swal.fire({ title: "Error", text: "No se pudo conectar con el servidor", icon: "error" });
                    }
                });
            }
        });
    }

    // Exponer globalmente para el onclick
    window.eliminarCoordinador = eliminarCoordinador;

    // Inicio
    cargarTabla();

});