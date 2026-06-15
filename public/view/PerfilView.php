<?php
require_once __DIR__ . '/../controller/PerfilController.php';
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="icon" type="image/svg+xml" href="../assets/img/sra_logo.svg">
    <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/crearExamen.css">
    <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
    <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
    <script src="../assets/js/libs/JustValidate/justValidate.min.js"></script>
    <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>
    <script defer src="../assets/js/dashboard.js"></script>
    <script defer src="../assets/js/perfil.js"></script>
</head>
<body>
<?php
    $paginaActiva = '';
    include __DIR__ . '/../partials/sidebar.php';
?>
<main id="main-content">

    <!-- CAMBIO: un solo contenedor-form envolviendo todo, sin .row/.col que limitaban el ancho -->
    <div id="contenedor-form">

        <!-- Avatar y rol -->
        <div class="text-center mb-4">
            <div style="width:90px;height:90px;border-radius:50%;background:#6f42c1;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                <i class="fa-solid fa-user" style="font-size:40px;color:white;"></i>
            </div>
            <h4 class="mt-3 mb-0">
                <?php echo htmlspecialchars($coordinador['nombre'] . ' ' . $coordinador['apellido_p'] . ' ' . ($coordinador['apellido_m'] ?? '')); ?>
            </h4>
            <span class="badge mt-1" style="background:#6f42c1;">
                <?php echo $_SESSION['rol'] === 'gestion' ? 'Gestión' : 'Coordinador'; ?>
            </span>
            <?php if (!empty($coordinador['departamento'])): ?>
                <p class="text-muted mt-2 mb-0">
                    <i class="fa-solid fa-building"></i> <?php echo htmlspecialchars($coordinador['departamento']); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Formulario de datos -->
        <h2><i class="fa-solid fa-id-card"></i> Información personal</h2>

        <form id="form-datos" autocomplete="off">
            <div class="row g-3">

                <div class="col-12 col-md-6">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                           value="<?php echo htmlspecialchars($coordinador['nombre']); ?>">
                </div>

                <div class="col-12 col-md-3">
                    <label for="apellido_p">Apellido Paterno</label>
                    <input type="text" id="apellido_p" name="apellido_p" class="form-control"
                           value="<?php echo htmlspecialchars($coordinador['apellido_p']); ?>">
                </div>

                <div class="col-12 col-md-3">
                    <label for="apellido_m">Apellido Materno</label>
                    <input type="text" id="apellido_m" name="apellido_m" class="form-control"
                           value="<?php echo htmlspecialchars($coordinador['apellido_m'] ?? ''); ?>">
                </div>

                <div class="col-12">
                    <label for="correo">Correo</label>
                    <input type="email" id="correo" name="correo" class="form-control"
                           value="<?php echo htmlspecialchars($coordinador['correo']); ?>">
                </div>

            </div>

            <input type="hidden" name="accion" value="actualizar_datos">

            <div class="mt-3">
                <button type="submit" id="btn-crear">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                </button>
            </div>
        </form>

        <!-- CAMBIO: separador entre secciones, ya no es un contenedor-form propio -->
        <hr class="my-4">

        <!-- Formulario de contraseña -->
        <h2><i class="fa-solid fa-key"></i> Cambiar contraseña</h2>

        <form id="form-contrasena" autocomplete="off">
            <div class="row g-3">

                <div class="col-12">
                    <label for="contrasena_actual">Contraseña actual</label>
                    <input type="password" id="contrasena_actual" name="contrasena_actual" class="form-control">
                </div>

                <div class="col-12 col-md-6">
                    <label for="contrasena_nueva">Nueva contraseña</label>
                    <input type="password" id="contrasena_nueva" name="contrasena_nueva" class="form-control">
                </div>

                <div class="col-12 col-md-6">
                    <label for="contrasena_confirmar">Confirmar nueva contraseña</label>
                    <input type="password" id="contrasena_confirmar" name="contrasena_confirmar" class="form-control">
                </div>

            </div>

            <input type="hidden" name="accion" value="cambiar_contrasena">

            <div class="mt-3">
                <button type="submit" id="btn-crear">
                    <i class="fa-solid fa-key"></i> Cambiar contraseña
                </button>
            </div>
        </form>

    </div>
</main>
</body>
</html>