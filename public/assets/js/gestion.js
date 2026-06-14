$(document).ready(() => {

    // SALONES

    let salonesData = [];

    function cargarSalones() {
        $.ajax({
            url: "../controller/SalonController.php",
            method: 'GET',
            dataType: 'json',
            success: (salones) => {
                salonesData = salones;
                renderSalones(salones);
            },
            error: () => console.error("Error al cargar salones")
        });
    }

    function renderSalones(salones) {
        const tbody = document.getElementById("tbody-salones");
        if (salones.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="texto-center">No hay salones registrados</td></tr>';
            return;
        }
        tbody.innerHTML = salones.map(s => `
            <tr>
                <td hidden>${s.id_salon}</td>
                <td>Salón ${s.edificio}${s.salon}</td>
                <td>${s.laboratorio == 1 ? 'Laboratorio' : 'Aula'}</td>
                <td>
                    <button class="btn-editar" onclick="editarSalon(${s.id_salon}, '${s.edificio}', '${s.salon}', ${s.laboratorio})">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="btn-eliminar" onclick="eliminarSalon(${s.id_salon})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join("");
    }

    // Filtro por texto — salones
    document.getElementById("filtro-salon-texto")
        .addEventListener("input", function() {
            const texto = this.value.toLowerCase();
            const filtrados = salonesData.filter(s =>
                (s.edificio + s.salon).toLowerCase().includes(texto)
            );
            renderSalones(filtrados);
        });

    window.mostrarFormSalon = function() {
        document.getElementById("form-salon-contenedor").style.display = "block";
        document.getElementById("salon-accion").value = "agregar";
        document.getElementById("salon-id").value = "";
        document.getElementById("form-salon").reset();
    }

    window.editarSalon = function(id, edificio, salon, laboratorio) {
        document.getElementById("form-salon-contenedor").style.display = "block";
        document.getElementById("salon-accion").value = "editar";
        document.getElementById("salon-id").value = id;
        document.getElementById("salon-edificio").value = edificio;
        document.getElementById("salon-numero").value = salon;
        document.getElementById("salon-laboratorio").checked = laboratorio == 1;
    }

    window.cancelarFormSalon = function() {
        document.getElementById("form-salon-contenedor").style.display = "none";
        document.getElementById("form-salon").reset();
    }

    $("#form-salon").on("submit", function(e) {
        e.preventDefault();

        const edificio = document.getElementById("salon-edificio").value.trim();
        const salon    = document.getElementById("salon-numero").value.trim();

        if (!/^\d$/.test(edificio)) {
            Swal.fire({ title: "Error", text: "El edificio debe ser un solo dígito (ej. 1)", icon: "error" });
            return;
        }

        if (!/^\d{3}$/.test(salon)) {
            Swal.fire({ title: "Error", text: "El número de salón debe tener exactamente 3 dígitos (ej. 001)", icon: "error" });
            return;
        }

        $.ajax({
            url: "../controller/SalonController.php",
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: (respuesta) => {
                Swal.fire({
                    title: "SRA",
                    text: respuesta.msj,
                    icon: respuesta.icono,
                    didDestroy: () => {
                        if (respuesta.cod) {
                            cancelarFormSalon();
                            cargarSalones();
                        }
                    }
                });
            },
            error: () => Swal.fire({ title: "Error", text: "No se pudo conectar", icon: "error" })
        });
    });

    window.eliminarSalon = function(id) {
        Swal.fire({
            title: "¿Eliminar salón?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/SalonController.php",
                    method: "POST",
                    data: { accion: "eliminar", id_salon: id },
                    dataType: "json",
                    success: (respuesta) => {
                        Swal.fire({
                            title: "SRA",
                            text: respuesta.msj,
                            icon: respuesta.icono,
                            didDestroy: () => { if (respuesta.cod) cargarSalones(); }
                        });
                    },
                    error: () => Swal.fire({ title: "Error", text: "No se pudo conectar", icon: "error" })
                });
            }
        });
    }

    // MATERIAs

    let materiasData = [];

    function cargarMaterias() {
        $.ajax({
            url: "../controller/MateriaController.php",
            method: 'GET',
            data: { completo: 1 },
            dataType: 'json',
            success: (materias) => {
                materiasData = materias;
                renderMaterias(materias);
            },
            error: () => console.error("Error al cargar materias")
        });
    }

    function renderMaterias(materias) {
        const tbody = document.getElementById("tbody-materias");
        if (materias.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="texto-center">No hay materias registradas</td></tr>';
            return;
        }
        tbody.innerHTML = materias.map(m => `
            <tr>
                <td hidden>${m.id_materia}</td>
                <td>${m.nombre}</td>
                <td>${m.carrera}</td>
                <td>${m.semestre}°</td>
                <td>${m.departamento}</td>
                <td>
                    <a href="./EditarMateriaView.php?id=${m.id_materia}" class="btn-editar">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <button class="btn-eliminar" onclick="eliminarMateria(${m.id_materia})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join("");
    }

    // Filtro por texto — materias
    document.getElementById("filtro-materia-texto")
        .addEventListener("input", function() {
            const texto = this.value.toLowerCase();
            const filtradas = materiasData.filter(m =>
                m.nombre.toLowerCase().includes(texto)
            );
            renderMaterias(filtradas);
        });

    window.eliminarMateria = function(id) {
        Swal.fire({
            title: "¿Eliminar materia?",
            text: "Esta acción no se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/MateriaController.php",
                    method: "POST",
                    data: { accion: "eliminar", id_materia: id },
                    dataType: "json",
                    success: (respuesta) => {
                        Swal.fire({
                            title: "SRA",
                            text: respuesta.msj,
                            icon: respuesta.icono,
                            didDestroy: () => { if (respuesta.cod) cargarMaterias(); }
                        });
                    },
                    error: () => Swal.fire({ title: "Error", text: "No se pudo conectar", icon: "error" })
                });
            }
        });
    }

    // Inicio
    cargarSalones();
    cargarMaterias();

});