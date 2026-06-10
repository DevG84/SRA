<?php
// Verificar que hay sesión activa
// Este archivo se incluye al inicio de cada vista del admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_coordinador'])) {
    header('Location: ../view/LoginView.php');
    exit;
}