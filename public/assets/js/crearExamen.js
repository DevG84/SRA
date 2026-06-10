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
        },
        error: () => {
            Swal.fire({
            title: "Error",
            text: "No se pudo cargar las carreras",
            icon: "error"
            });
        }
        });
    }

    // Cargar materias al cambiar de carrera o semestre
    function cargarMaterias() {
      const idCarrera = document.getElementById("id_carrera").value;
      const idSemestre = document.getElementById("id_semestre").value;

      // Limpiar el select de materias antes de cargar nuevas opciones
      const select = document.getElementById("id_materia");
      select.innerHTML = '<option value="">Seleccionar Materia</option>';

        if (!idCarrera) {
          return;
        }

        $.ajax({
        url: "../controller/MateriaController.php",
        method: 'GET',
        data: { carrera: idCarrera, semestre: idSemestre },
        dataType: 'json',
            success: (materias) => {
                materias.forEach(materia => {
                const option = document.createElement("option");
                option.value = materia.id_materia;
                option.textContent = materia.nombre;
                select.appendChild(option);
                });
            },
            error: () => {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo cargar las materias",
                    icon: "error"
                });
            }
        });
    }

    // Cargar Salones
    function cargarSalones() {
        $.ajax({
        url: "../controller/SalonController.php",
        method: 'GET',
        dataType: 'json',
            success: (salones) => {
                const select = document.getElementById("id_salon"); 
                salones.forEach(salon => {
                    const option = document.createElement("option");
                    option.value = salon.id_salon;
                    const tipo = salon.laboratorio == 1 ? " (LAB)" : ""; 
                    option.textContent = "Salon " + salon.edificio + salon.salon + tipo;
                    select.appendChild(option);
                });
            },
            error: () => {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo cargar los salones",
                    icon: "error"
                });
            }
        });
    }

    // EVENTOS
    document.getElementById("id_carrera").addEventListener("change", cargarMaterias);
    document.getElementById("id_semestre").addEventListener("change", cargarMaterias);

    // VALIDACIÓN Y SUBMIT
    const validator = new JustValidate("#form-crear-examen", {
        errorFieldCssClass: "is-invalid",
        successFieldCssClass: "is-valid"
    });

    validator
        .addField("#id_carrera",  [{ rule: "required", errorMessage: "Selecciona una carrera" }])
        .addField("#id_semestre", [{ rule: "required", errorMessage: "Selecciona un semestre" }])
        .addField("#id_materia",  [{ rule: "required", errorMessage: "Selecciona una materia" }])
        .addField("#id_salon",    [{ rule: "required", errorMessage: "Selecciona un salón" }])
        .addField("#fecha",       [{ rule: "required", errorMessage: "Selecciona una fecha" }])
        .addField("#turno",       [{ rule: "required", errorMessage: "Selecciona un turno" }])
        .addField("#horario",     [{ rule: "required", errorMessage: "Escribe el horario" }])
        .onSuccess((e) => {
        const formData = new FormData(document.getElementById("form-crear-examen"));
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
                    if (respuesta.cod) $("#form-crear-examen")[0].reset();
                }
                });
            },
            error: () => {
                Swal.fire({ title: "Error", text: "No se pudo conectar con el servidor", icon: "error" });
            }
        });
    });

    // Cargar datos al iniciar
    cargarCarreras();
    cargarSalones();
});