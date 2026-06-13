<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Si ya hay sesión activa, redirigir al dashboard
    if (isset($_SESSION['id_coordinador'])) {
        header('Location: ./DashboardView.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Regularización Académica</title>

    <link rel="stylesheet" href="../assets/css/libs/bootstrap-5.3.8/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/theme.scss">
    <link rel="stylesheet" href="../assets/css/login.css">

    <script src="../assets/js/libs/Jquery/jquery-4.0.min.js"></script>
    
    <script src="../assets/js/libs/SweetAlert/sweetalert2.all.min.js"></script>
    <script src="../assets/js/libs/JustValidate/justValidate.min.js"></script>
    
    <script defer src="../assets/js/libs/bootstrap-5.3.8/bootstrap.bundle.js"></script>

    <script defer src="../assets/js/login.js"></script>
</head>
<body>
    <header>
        <nav class="container navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">SRA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/">Iniciar sesión</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="https://www.escom.ipn.mx/">ESCOMunidad</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="">Avisos</a>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Más información
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Reglamento</a></li>
                    <li><a class="dropdown-item" href="#">Calendario</a></li>
                    <li><a class="dropdown-item" href="#">Costos y formas de pago</a></li>
                    <li><a class="dropdown-item" href="#">Preguntas frecuentes</a></li>
                    <li><a class="dropdown-item" href="#">Directorio</a></li>
                </ul>
                </li>
            </ul>
            </div>
        </div>
        </nav>
    </header>
    <main>
        <form id="login" autocomplete="off">
            <h1>Sistema de Regularización<br>Académica</h1>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="Correo" name="correo">
                <label for="floatingInput">Correo</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Contraseña" name="password">
                <label for="floatingPassword">Contraseña</label>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Recordar mi usuario</label>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            <a href="" class="loginlabel aria-label">Regístrate</a>
        </form>
    </main>
    <footer>
        <div id="footer">
        <div class="container">
            <p class="text-muted credit">© 2026 SRA Alumnos de ESCOM</p>
        </div>
        </div>
    </footer>
</body>
</html>