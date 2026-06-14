$(document).ready(() => {

    // Cargar carreras
    function cargarCarreras() {
        $.ajax({
            url: "../controller/CarreraController.php",
            method: 'GET',
            dataType: 'json',
            success: (carreras) => {
                const select = document.getElementById("id_carrera");
                carreras.forEach(carrera => {
                    const option = document.createElement("option");
                    option.value = carrera.id_carrera;
                    option.textContent = carrera.nombre + " (" + carrera.alias + ")";
                    select.appendChild(option);
                });
                // Preseleccionar carrera del examen
                preseleccionarCarrera();
            },
            error: () => {}
        });
    }

    // Cargar materias según carrera y semestre
    function cargarMaterias() {
        const idCarrera  = document.getElementById("id_carrera").value;
        const idSemestre = document.getElementById("id_semestre").value;

        const select = document.getElementById("id_materia");
        select.innerHTML = '<option value="">Seleccionar Materia</option>';

        if (!idCarrera) return;

        $.ajax({
            url: "../controller/MateriaController.php",
            method: 'GET',
            data: { carrera: idCarrera, semestre: idSemestre },
            dataType: 'json',
            success: (materias) => {
                const idMateria = document.getElementById("data-id_materia").value;
                materias.forEach(materia => {
                    const option = document.createElement("option");
                    option.value = materia.id_materia;
                    option.textContent = materia.nombre;
                    // Seleccionar la materia del examen
                    if (materia.id_materia == idMateria) option.selected = true;
                    select.appendChild(option);
                });
            },
            error: () => {}
        });
    }

    // Cargar salones
    function cargarSalones() {
        $.ajax({
            url: "../controller/SalonController.php",
            method: 'GET',
            dataType: 'json',
            success: (salones) => {
                const select  = document.getElementById("id_salon");
                const idSalon = document.getElementById("data-id_salon").value;
                salones.forEach(salon => {
                    const option = document.createElement("option");
                    option.value = salon.id_salon;
                    const tipo = salon.laboratorio == 1 ? " (LAB)" : "";
                    option.textContent = salon.edificio + salon.salon + tipo;
                    // Seleccionar el salón del examen
                    if (salon.id_salon == idSalon) option.selected = true;
                    select.appendChild(option);
                });
            },
            error: () => {}
        });
    }

    // Obtener carrera de la materia actual y preseleccionarla
    function preseleccionarCarrera() {
        const idMateria = document.getElementById("data-id_materia").value;
        $.ajax({
            url: "../controller/MateriaController.php",
            method: 'GET',
            data: { id_materia: idMateria },
            dataType: 'json',
            success: (data) => {
                if (data.id_carrera) {
                    document.getElementById("id_carrera").value = data.id_carrera;
                    document.getElementById("id_semestre").value = data.semestre;
                    cargarMaterias();
                }
            },
            error: () => {}
        });
    }

    function cargarCoordinadores() {
        const select = document.getElementById("id_coordinador");
        if (!select) return;

        const idActual = document.getElementById("data-id_coordinador").value;

        $.ajax({
            url: "../controller/CoordinadorController.php",
            method: 'GET',
            dataType: 'json',
            success: (coordinadores) => {
                coordinadores.forEach(c => {
                    const option = document.createElement("option");
                    option.value = c.id_coordinador;
                    option.textContent = c.nombre + " " + c.apellido_p;
                    if (c.id_coordinador == idActual) option.selected = true;
                    select.appendChild(option);
                });
            },
            error: () => {}
        });
}

    // Limpiar archivo actual
    function limpiarArchivo(campo, campoActual, parrafo) {
        document.getElementById(campoActual).value = "";
        document.getElementById(campo).value = null;
        document.getElementById(parrafo).style.display = "none";
    }

    // Eventos
    document.getElementById("id_carrera").addEventListener("change", cargarMaterias);
    document.getElementById("id_semestre").addEventListener("change", cargarMaterias);

    // Validación y submit
    const validator = new JustValidate("#form-editar-examen", {
        errorFieldCssClass: "is-invalid",
        successFieldCssClass: "is-valid"
    });

    if (document.getElementById("id_coordinador")) {
        validator.addField("#id_coordinador", [{ rule: "required", errorMessage: "Selecciona un coordinador" }]);
    }

    validator
        .addField("#id_carrera",  [{ rule: "required", errorMessage: "Selecciona una carrera" }])
        .addField("#id_semestre", [{ rule: "required", errorMessage: "Selecciona un semestre" }])
        .addField("#id_materia",  [{ rule: "required", errorMessage: "Selecciona una materia" }])
        .addField("#id_salon",    [{ rule: "required", errorMessage: "Selecciona un salón" }])
        .addField("#fecha",       [{ rule: "required", errorMessage: "Selecciona una fecha" }])
        .addField("#turno",       [{ rule: "required", errorMessage: "Selecciona un turno" }])
        .addField("#horario",     [{ rule: "required", errorMessage: "Escribe el horario" }])
        .onSuccess((e) => {
            const formData = new FormData(document.getElementById("form-editar-examen"));
            $.ajax({
                url: "../controller/ExamenController.php",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: (respuesta) => {
                    Swal.fire({
                        title: "SRA",
                        text: respuesta.msj,
                        icon: respuesta.icono,
                        didDestroy: () => {
                            if (respuesta.cod) window.location.href = "./dashboard.php";
                        }
                    });
                },
                error: () => {
                    Swal.fire({ title: "Error", text: "No se pudo conectar con el servidor", icon: "error" });
                }
            });
        });

    // Inicio
    cargarCarreras();
    cargarSalones();
    cargarCoordinadores();

});