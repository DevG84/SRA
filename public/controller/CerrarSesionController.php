<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();     // limpiar variables
session_destroy();   // destruir sesión
session_start();     // nueva sesión vacía
session_regenerate_id(true); // nuevo ID

// Redirigir al login
header('Location: ../view/LoginView.php');
exit;