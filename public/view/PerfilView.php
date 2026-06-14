<?php
require_once __DIR__ . '/../../controller/PerfilController.php';
?>
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>
</head>
<body>
<?php
    $paginaActiva = '';
    include __DIR__ . '/../partials/sidebar.php';
?>
<div id="main-content">
    <div id="bienvenida">
        <h2><i class="fa-solid fa-user me-2"></i>Mi Perfil</h2>
        <p class="text-muted">Información de tu cuenta</p>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card bg-body-tertiary border-0 shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div style="width:90px;height:90px;border-radius:50%;background:#6f42c1;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="fa-solid fa-user" style="font-size:40px;color:white;"></i>
                        </div>
                        <h4 class="mt-3 mb-0">
                            <?php echo htmlspecialchars($coordinador['nombre'] . ' ' . $coordinador['apellido_p'] . ' ' . ($coordinador['apellido_m'] ?? '')); ?>
                        </h4>
                        <span class="badge mt-1" style="background:#6f42c1;">Coordinador</span>
                    </div>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent d-flex align-items-center gap-3 py-3">
                            <i class="fa-solid fa-user" style="color:#6f42c1;"></i>
                            <div>
                                <small class="text-muted d-block">Nombre completo</small>
                                <strong><?php echo htmlspecialchars($coordinador['nombre'] . ' ' . $coordinador['apellido_p'] . ' ' . ($coordinador['apellido_m'] ?? '')); ?></strong>
                            </div>
                        </li>
                        <li class="list-group-item bg-transparent d-flex align-items-center gap-3 py-3">
                            <i class="fa-solid fa-envelope" style="color:#6f42c1;"></i>
                            <div>
                                <small class="text-muted d-block">Correo electrónico</small>
                                <strong><?php echo htmlspecialchars($coordinador['correo']); ?></strong>
                            </div>
                        </li>
                        <li class="list-group-item bg-transparent d-flex align-items-center gap-3 py-3">
                            <i class="fa-solid fa-building" style="color:#6f42c1;"></i>
                            <div>
                                <small class="text-muted d-block">Departamento</small>
                                <strong><?php echo htmlspecialchars($coordinador['departamento'] ?? 'No asignado'); ?></strong>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
