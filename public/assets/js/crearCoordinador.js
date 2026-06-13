$(document).ready(() => {

    const validator = new JustValidate("#form-crear-coordinador", {
        errorFieldCssClass: "is-invalid",
        successFieldCssClass: "is-valid"
    });

    validator
        .addField("#nombre",          [{ rule: "required", errorMessage: "Falta el nombre" }])
        .addField("#apellido_p",      [{ rule: "required", errorMessage: "Falta el apellido paterno" }])
        .addField("#id_departamento", [{ rule: "required", errorMessage: "Selecciona un departamento" }])
        .addField("#correo", [
            { rule: "required", errorMessage: "Falta el correo" },
            { rule: "email",    errorMessage: "Formato de correo incorrecto" }
        ])
        .addField("#contrasena", [
            { rule: "required",   errorMessage: "Falta la contraseña" },
            { rule: "minLength",  value: 6, errorMessage: "Mínimo 6 caracteres" }
        ])
        .addField("#confirmar_contrasena", [
            { rule: "required", errorMessage: "Confirma la contraseña" }
        ])
        .onSuccess((e) => {

            // Verificar que las contraseñas coincidan
            const pass  = document.getElementById("contrasena").value;
            const pass2 = document.getElementById("confirmar_contrasena").value;

            if (pass !== pass2) {
                Swal.fire({ title: "Error", text: "Las contraseñas no coinciden", icon: "error" });
                return;
            }

            $.ajax({
                url: "../controller/CoordinadorController.php",
                method: "POST",
                data: $("#form-crear-coordinador").serialize(),
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