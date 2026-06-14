$(document).ready(() => {

    const validatorDatos = new JustValidate("#form-datos", {
        errorFieldCssClass: "is-invalid",
        successFieldCssClass: "is-valid"
    });

    validatorDatos
        .addField("#nombre",     [{ rule: "required", errorMessage: "Falta el nombre" }])
        .addField("#apellido_p", [{ rule: "required", errorMessage: "Falta el apellido paterno" }])
        .addField("#apellido_m", [{ rule: "required", errorMessage: "Falta el apellido materno" }])
        .addField("#correo", [
            { rule: "required", errorMessage: "Falta el correo" },
            { rule: "email",    errorMessage: "Formato de correo incorrecto" }
        ])
        .onSuccess((e) => {
            $.ajax({
                url: "../controller/PerfilController.php",
                method: "POST",
                data: $("#form-datos").serialize(),
                dataType: "json",
                success: (respuesta) => {
                    Swal.fire({
                        title: "SRA",
                        text: respuesta.msj,
                        icon: respuesta.icono
                    });
                },
                error: () => {
                    Swal.fire({ title: "Error", text: "No se pudo conectar con el servidor", icon: "error" });
                }
            });
        });

    const validatorContrasena = new JustValidate("#form-contrasena", {
        errorFieldCssClass: "is-invalid",
        successFieldCssClass: "is-valid"
    });

    validatorContrasena
        .addField("#contrasena_actual", [
            { rule: "required", errorMessage: "Ingresa tu contraseña actual" }
        ])
        .addField("#contrasena_nueva", [
            { rule: "required",  errorMessage: "Ingresa la nueva contraseña" },
            { rule: "minLength", value: 6, errorMessage: "Mínimo 6 caracteres" }
        ])
        .addField("#contrasena_confirmar", [
            { rule: "required", errorMessage: "Confirma la nueva contraseña" }
        ])
        .onSuccess((e) => {

            const nueva   = document.getElementById("contrasena_nueva").value;
            const confirm = document.getElementById("contrasena_confirmar").value;

            if (nueva !== confirm) {
                Swal.fire({ title: "Error", text: "Las contraseñas nuevas no coinciden", icon: "error" });
                return;
            }

            $.ajax({
                url: "../controller/PerfilController.php",
                method: "POST",
                data: $("#form-contrasena").serialize(),
                dataType: "json",
                success: (respuesta) => {
                    Swal.fire({
                        title: "SRA",
                        text: respuesta.msj,
                        icon: respuesta.icono,
                        didDestroy: () => {
                            if (respuesta.cod) {
                                document.getElementById("form-contrasena").reset();
                            }
                        }
                    });
                },
                error: () => {
                    Swal.fire({ title: "Error", text: "No se pudo conectar con el servidor", icon: "error" });
                }
            });
        });

});