$(document).ready(() => {

    const FILAS_POR_PAGINA = 10;

    // ================================================
    // FUNCIÓN GENÉRICA DE PAGINACIÓN
    // ================================================
    function renderPaginacion(contenedorId, datos, paginaActual, onCambioPagina) {
        const totalPaginas = Math.max(1, Math.ceil(datos.length / FILAS_POR_PAGINA));
        const contenedor = document.getElementById(contenedorId);

        if (totalPaginas <= 1) {
            contenedor.innerHTML = '';
            return;
        }

        let botones = '';

        // Botón anterior
        botones += `<button class="btn-pagina" ${paginaActual === 1 ? 'disabled' : ''} data-pagina="${paginaActual - 1}">
            <i class="fa-solid fa-chevron-left"></i>
        </button>`;

        // Números de página (máximo 5 visibles, centrados en la actual)
        let inicio = Math.max(1, paginaActual - 2);
        let fin = Math.min(totalPaginas, inicio + 4);
        inicio = Math.max(1, fin - 4);

        if (inicio > 1) {
            botones += `<button class="btn-pagina" data-pagina="1">1</button>`;
            if (inicio > 2) botones += `<span class="pagina-separador">...</span>`;
        }

        for (let p = inicio; p <= fin; p++) {
            botones += `<button class="btn-pagina ${p === paginaActual ? 'activa' : ''}" data-pagina="${p}">${p}</button>`;
        }

        if (fin < totalPaginas) {
            if (fin < totalPaginas - 1) botones += `<span class="pagina-separador">...</span>`;
            botones += `<button class="btn-pagina" data-pagina="${totalPaginas}">${totalPaginas}</button>`;
        }

        // Botón siguiente
        botones += `<button class="btn-pagina" ${paginaActual === totalPaginas ? 'disabled' : ''} data-pagina="${paginaActual + 1}">
            <i class="fa-solid fa-chevron-right"></i>
        </button>`;

        // Info de resultados
        const desde = (paginaActual - 1) * FILAS_POR_PAGINA + 1;
        const hasta = Math.min(paginaActual * FILAS_POR_PAGINA, datos.length);
        const info = `<span class="pagina-info">Mostrando ${desde}-${hasta} de ${datos.length}</span>`;

        contenedor.innerHTML = `<div class="paginacion-controles">${info}<div class="paginacion-botones">${botones}</div></div>`;

        contenedor.querySelectorAll('.btn-pagina:not([disabled])').forEach(btn => {
            btn.addEventListener('click', () => onCambioPagina(parseInt(btn.dataset.pagina)));
        });
    }

    function obtenerPagina(datos, pagina) {
        const inicio = (pagina - 1) * FILAS_POR_PAGINA;
        return datos.slice(inicio, inicio + FILAS_POR_PAGINA);
    }

    // ================================================
    // SALONES
    // ================================================

    let salonesData = [];
    let salonesFiltrados = [];
    let paginaSalones = 1;

    function cargarSalones() {
        $.ajax({
            url: "../controller/SalonController.php",
            method: 'GET',
            dataType: 'json',
            success: (salones) => {
                salonesData = salones;
                salonesFiltrados = salones;
                paginaSalones = 1;
                renderSalones();
            },
            error: () => console.error("Error al cargar salones")
        });
    }

    function renderSalones() {
        const tbody = document.getElementById("tbody-salones");

        if (salonesFiltrados.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="texto-center">No hay salones registrados</td></tr>';
            renderPaginacion('paginacion-salones', salonesFiltrados, paginaSalones, cambiarPaginaSalones);
            return;
        }

        const pagina = obtenerPagina(salonesFiltrados, paginaSalones);

        tbody.innerHTML = pagina.map(s => `
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

        renderPaginacion('paginacion-salones', salonesFiltrados, paginaSalones, cambiarPaginaSalones);
    }

    function cambiarPaginaSalones(nuevaPagina) {
        paginaSalones = nuevaPagina;
        renderSalones();
    }

    // Filtro por texto — salones
    document.getElementById("filtro-salon-texto")
        .addEventListener("input", function() {
            const texto = this.value.toLowerCase();
            salonesFiltrados = salonesData.filter(s =>
                (s.edificio + s.salon).toLowerCase().includes(texto)
            );
            paginaSalones = 1;
            renderSalones();
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

    // ================================================
    // MATERIAS
    // ================================================

    let materiasData = [];
    let materiasFiltradas = [];
    let paginaMaterias = 1;

    function cargarMaterias() {
        $.ajax({
            url: "../controller/MateriaController.php",
            method: 'GET',
            data: { completo: 1 },
            dataType: 'json',
            success: (materias) => {
                materiasData = materias;
                materiasFiltradas = materias;
                paginaMaterias = 1;
                renderMaterias();
            },
            error: () => console.error("Error al cargar materias")
        });
    }

    function renderMaterias() {
        const tbody = document.getElementById("tbody-materias");

        if (materiasFiltradas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="texto-center">No hay materias registradas</td></tr>';
            renderPaginacion('paginacion-materias', materiasFiltradas, paginaMaterias, cambiarPaginaMaterias);
            return;
        }

        const pagina = obtenerPagina(materiasFiltradas, paginaMaterias);

        tbody.innerHTML = pagina.map(m => `
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

        renderPaginacion('paginacion-materias', materiasFiltradas, paginaMaterias, cambiarPaginaMaterias);
    }

    function cambiarPaginaMaterias(nuevaPagina) {
        paginaMaterias = nuevaPagina;
        renderMaterias();
    }

    // Filtro por texto — materias
    document.getElementById("filtro-materia-texto")
        .addEventListener("input", function() {
            const texto = this.value.toLowerCase();
            materiasFiltradas = materiasData.filter(m =>
                m.nombre.toLowerCase().includes(texto)
            );
            paginaMaterias = 1;
            renderMaterias();
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