<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['id_coordinador'])) {
    header('Location: ../public/view/LoginView.php');
    exit();
}

$id = $_SESSION['id_coordinador'];
$pdo = (new Connection)->connect();

$stmt = $pdo->prepare("
    SELECT c.id_coordinador, c.nombre, c.apellido_p, c.apellido_m,
           c.correo, d.nombre AS departamento
    FROM coordinador c
    JOIN departamento d ON c.id_departamento = d.id_departamento
    WHERE c.id_coordinador = ?
");
$stmt->execute([$id]);
$coordinador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coordinador) {
    header('Location: ../public/view/LoginView.php');
    exit();
}
?>
