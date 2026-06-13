<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña — SRA ESCOM</title>
  <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
  <link rel="stylesheet" href="../assets/css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
  <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
  <script src="../assets/js/libs/JustValidate/justValidate.min.js"></script>
  <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>

  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background-color: var(--bs-body-bg);
    }

    #contenedor {
      background-color: var(--bs-tertiary-bg);
      border-radius: 12px;
      padding: 2rem;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    #contenedor h2 {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    #contenedor p {
      font-size: 0.9rem;
      color: var(--bs-secondary-color);
      margin-bottom: 1.5rem;
    }

    #btn-enviar {
      background-color: #0071AA;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 0.65rem 1.5rem;
      font-size: 0.95rem;
      font-weight: 500;
      width: 100%;
      cursor: pointer;
      transition: background-color 0.2s;
      margin-top: 1rem;
    }

    #btn-enviar:hover {
      background-color: #006395;
    }

    .link-volver {
      display: block;
      text-align: center;
      margin-top: 1rem;
      font-size: 0.875rem;
      color: var(--bs-secondary-color);
      text-decoration: none;
    }

    .link-volver:hover {
      color: var(--bs-body-color);
    }
  </style>
</head>
<body>

  <div id="contenedor">
    <h2><i class="fa-solid fa-key"></i> Recuperar contraseña</h2>
    <p>Ingresa tu correo registrado y te enviaremos una nueva contraseña.</p>

    <form id="form-recuperar" autocomplete="off">
      <div class="mb-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" id="correo" name="correo" class="form-control"
               placeholder="correo@ipn.mx">
      </div>
      <button type="submit" id="btn-enviar">
        <i class="fa-solid fa-paper-plane"></i> Enviar nueva contraseña
      </button>
    </form>

    <a href="./LoginView.php" class="link-volver">
      ← Volver al inicio de sesión
    </a>
  </div>

  <script>
    $(document).ready(() => {
        const validator = new JustValidate("#form-recuperar", {
            errorFieldCssClass: "is-invalid",
            successFieldCssClass: "is-valid"
        });

        validator
            .addField("#correo", [
                { rule: "required", errorMessage: "Falta el correo" },
                { rule: "email",    errorMessage: "Formato de correo incorrecto" }
            ])
            .onSuccess((e) => {
                const btn = document.getElementById("btn-enviar");
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Enviando...';

                $.ajax({
                    url: "../controller/RecuperarContrasenaController.php",
                    method: "POST",
                    data: $("#form-recuperar").serialize(),
                    dataType: "json",
                    success: (respuesta) => {
                        Swal.fire({
                            title: "SRA",
                            text: respuesta.msj,
                            icon: respuesta.icono,
                            didDestroy: () => {
                                if (respuesta.cod) window.location.href = "./LoginView.php";
                                else {
                                    btn.disabled = false;
                                    btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Enviar nueva contraseña';
                                }
                            }
                        });
                    },
                    error: () => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Enviar nueva contraseña';
                        Swal.fire({ title: "Error", text: "No se pudo conectar con el servidor", icon: "error" });
                    }
                });
            });
    });
  </script>

</body>
</html>