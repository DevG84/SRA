$(document).ready(() => {

    const validator = new JustValidate("#form-crear-materia", {
        errorFieldCssClass: "is-invalid",
        successFieldCssClass: "is-valid"
    });

    validator
        .addField("#nombre",          [{ rule: "required", errorMessage: "Falta el nombre de la materia" }])
        .addField("#id_carrera",      [{ rule: "required", errorMessage: "Selecciona una carrera" }])
        .addField("#semestre",        [{ rule: "required", errorMessage: "Selecciona un semestre" }])
        .addField("#id_departamento", [{ rule: "required", errorMessage: "Selecciona un departamento" }])
        .onSuccess((e) => {
            $.ajax({
                url: "../controller/MateriaController.php",
                method: "POST",
                data: $("#form-crear-materia").serialize(),
                dataType: "json",
                success: (respuesta) => {
                    Swal.fire({
                        title: "SRA",
                        text: respuesta.msj,
                        icon: respuesta.icono,
                        didDestroy: () => {
                            if (respuesta.cod) window.location.href = "./GestionDashboardView.php";
                        }
                    });
                },
                error: () => {
                    Swal.fire({ title: "Error", text: "No se pudo conectar con el servidor", icon: "error" });
                }
            });
        });

});