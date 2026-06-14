$(document).ready(() => {

    const validator = new JustValidate("#form-editar-alumno", {
        errorFieldCssClass: "is-invalid",
        successFieldCssClass: "is-valid"
    });

    validator
        .addField("#boleta", [
            { rule: "required",  errorMessage: "Falta la boleta" },
            { rule: "customRegexp", value: /^\d{10}$/, errorMessage: "La boleta debe tener 10 dígitos" }
        ])
        .addField("#nombre",     [{ rule: "required", errorMessage: "Falta el nombre" }])
        .addField("#apellido_p", [{ rule: "required", errorMessage: "Falta el apellido paterno" }])
        .addField("#apellido_m", [{ rule: "required", errorMessage: "Falta el apellido materno" }])
        .onSuccess((e) => {
            $.ajax({
                url: "../controller/AlumnoController.php",
                method: "POST",
                data: $("#form-editar-alumno").serialize(),
                dataType: "json",
                success: (respuesta) => {
                    Swal.fire({
                        title: "SRA",
                        text: respuesta.msj,
                        icon: respuesta.icono,
                        didDestroy: () => {
                            if (respuesta.cod) window.location.href = "./AlumnosView.php";
                        }
                    });
                },
                error: () => {
                    Swal.fire({ title: "Error", text: "No se pudo conectar con el servidor", icon: "error" });
                }
            });
        });

});