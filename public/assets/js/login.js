$(document).ready(() => {
    const validator = new JustValidate("#login");
    validator.addField(
        '#floatingInput', [
        { rule: 'required', errorMessage: 'Se requiere un correo' },
        { rule: 'email', errorMessage: 'El correo no es válido' },
    ]).addField(
        '#floatingPassword', [
        {
            rule: 'required',
            errorMessage: 'Se requiere una contraseña'
        },
    ]).onSuccess((e) => {
        e.preventDefault();
        $.ajax({
            url: "/public/controller/LoginController.php",
            method: 'POST',
            data: $("#login").serialize(),
            dataType: 'json',
            cache: false,
            success: (respuesta) => {
                console.log(respuesta);
                Swal.fire({
                    title: "¡Hola!",
                    text: respuesta.msj,
                    icon: respuesta.icono,
                    didClose: () => {
                        respuesta.cod ? window.location.href = "/public/view/DashboardView.php" : window.location.reload();
                    }
                });
            }
        });
    });
});