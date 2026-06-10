<?php
// Verificar que hay sesión activa
// Este archivo se incluye al inicio de cada vista del admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['id_coordinador']) || empty($_SESSION['id_coordinador'])) {
    header("Location: /public/view/LoginView.php");
    header("Cache-Control: no-store");
    exit;
}