$(document).ready(() => {

    const validator = new JustValidate("#form-editar-coordinador", {
        errorFieldCssClass: "is-invalid",
        successFieldCssClass: "is-valid"
    });

    validator
        .addField("#nombre",          [{ rule: "required", errorMessage: "Falta el nombre" }])
        .addField("#apellido_p",      [{ rule: "required", errorMessage: "Falta el apellido paterno" }])
        .addField("#apellido_m",      [{ rule: "required", errorMessage: "Falta el apellido materno" }])
        .addField("#id_departamento", [{ rule: "required", errorMessage: "Selecciona un departamento" }])
        .addField("#correo", [
            { rule: "required", errorMessage: "Falta el correo" },
            { rule: "email",    errorMessage: "Formato de correo incorrecto" }
        ])
        .onSuccess((e) => {

            $.ajax({
                url: "../controller/CoordinadorController.php",
                method: "POST",
                data: $("#form-editar-coordinador").serialize(),
                dataType: "json",
                success: (respuesta) => {
                    Swal.fire({
                        title: "SRA",
                        text: respuesta.msj,
                        icon: respuesta.icono,
                        didDestroy: () => {
                            if (respuesta.cod) window.location.href = "./CoordinadoresView.php";
                        }
                    });
                },
                error: () => {
                    Swal.fire({ title: "Error", text: "No se pudo conectar con el servidor", icon: "error" });
                }
            });
        });

});