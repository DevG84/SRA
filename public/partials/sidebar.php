<?php
// Sidebar reutilizable
// Incluir en cada vista admin con: include __DIR__ . '/partials/sidebar.php';
// Definir $paginaActiva antes del include para marcar el link activo
// Ejemplo: $paginaActiva = 'crear'; // 'inicio', 'panel', 'crear', 'reportes', 'coordinadores', 'alumnos', 'gestion'
$paginaActiva = $paginaActiva ?? '';
$rol = $_SESSION['rol'] ?? 'coordinador';

function linkActivo($pagina, $paginaActiva) {
    return $pagina === $paginaActiva ? 'nav-link active d-flex align-items-center' : 'nav-link link-body-emphasis d-flex align-items-center';
}
?>
<div id="sidebar" class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary sidebar-transition sidebar-collapsed" style="height: 100vh;">

    <a id="toggle-sidebar" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none" style="cursor: pointer;">
        <span class="material-symbols-outlined me-2" style="font-size: 32px;">page_menu_ios</span>
        <span class="fs-4 nav-text">Sistema SRA</span>
    </a>

    <hr>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="./DashboardView.php" class="<?php echo linkActivo('inicio', $paginaActiva); ?>">
                <span class="material-symbols-outlined me-2">home</span>
                <span class="nav-text">Inicio</span>
            </a>
        </li>
        <li>
            <a href="./PanelView.php" class="<?php echo linkActivo('panel', $paginaActiva); ?>">
                <span class="material-symbols-outlined me-2">dashboard</span>
                <span class="nav-text">Panel de Control</span>
            </a>
        </li>
        <li>
            <a href="./CrearExamenView.php" class="<?php echo linkActivo('crear', $paginaActiva); ?>">
                <span class="material-symbols-outlined me-2">add_circle</span>
                <span class="nav-text">Crear Examen</span>
            </a>
        </li>
        <li>
            <a href="./ReportesView.php" class="<?php echo linkActivo('reportes', $paginaActiva); ?>">
                <span class="material-symbols-outlined me-2">lab_profile</span>
                <span class="nav-text">Reportes</span>
            </a>
        </li>
        <?php if ($rol === 'gestion'): ?>
        <li>
            <a href="./CoordinadoresView.php" class="<?php echo linkActivo('coordinadores', $paginaActiva); ?>">
                <span class="material-symbols-outlined me-2">manage_accounts</span>
                <span class="nav-text">Coordinadores</span>
            </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="./AlumnosView.php" class="<?php echo linkActivo('alumnos', $paginaActiva); ?>">
                <span class="material-symbols-outlined me-2">history_edu</span>
                <span class="nav-text">Alumnos</span>
            </a>
        </li>
        <?php if ($rol === 'gestion'): ?>
        <li>
            <a href="./GestionDashboardView.php" class="<?php echo linkActivo('gestion', $paginaActiva); ?>">
                <span class="material-symbols-outlined me-2">admin_panel_settings</span>
                <span class="nav-text">Gestión</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <hr>

    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-symbols-outlined me-2">account_circle</span>
            <strong class="nav-text"><?php echo $_SESSION['nombre'] ?? 'Desconocido'; ?></strong>
        </a>
        <ul class="dropdown-menu text-small shadow">
            <li>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <span class="material-symbols-outlined me-2" style="font-size: 18px;">person</span>
                    <span class="ms-2">Perfil</span>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item d-flex align-items-center text-danger" href="../controller/CerrarSesionController.php">
                    <span class="material-symbols-outlined me-2" style="font-size: 18px;">logout</span>
                    <span class="ms-2">Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>
</div>