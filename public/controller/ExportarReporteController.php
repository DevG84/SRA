<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../model/ReporteModel.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Requiere sesión activa
if (!isset($_SESSION['id_coordinador'])) {
    http_response_code(401);
    die("No autorizado");
}

$id_examen = $_GET['id_examen'] ?? '';

if (empty($id_examen)) {
    die("Falta el ID del examen");
}

require_once __DIR__ . '/../../includes/db.php';
$conn = (new Connection)->connect();

// Datos del examen
$stmt = $conn->prepare(
    "SELECT m.nombre AS materia, c.alias AS carrera, e.fecha, e.turno, e.horario,
            s.edificio, s.salon, co.nombre AS coordNombre, co.apellido_p AS coordApellidoP
     FROM examen e
     JOIN materia m   ON e.id_materia = m.id_materia
     JOIN carrera c   ON m.id_carrera = c.id_carrera
     JOIN salon s     ON e.id_salon = s.id_salon
     JOIN coordinador co ON e.id_coordinador = co.id_coordinador
     WHERE e.id_examen = :id"
);
$stmt->execute([':id' => $id_examen]);
$examen = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$examen) {
    die("Examen no encontrado");
}

// Alumnos inscritos
$modelo  = new ReporteModel();
$alumnos = $modelo->getAlumnosPorExamen($id_examen);

// Fecha formateada
$fecha = (new DateTime($examen['fecha']))->format('d/m/Y');

// GENERAR TABLA

$filasAlumnos = '';
if (empty($alumnos)) {
    $filasAlumnos = '<tr><td colspan="3" class="centro">No hay alumnos registrados</td></tr>';
} else {
    $i = 1;
    foreach ($alumnos as $a) {
        $nombreCompleto = htmlspecialchars($a['nombre'] . ' ' . $a['apellido_p'] . ' ' . ($a['apellido_m'] ?? ''));
        $filasAlumnos .= "<tr>
            <td class='centro'>" . htmlspecialchars($a['boleta']) . "</td>
            <td>{$nombreCompleto}</td>
        </tr>";
        $i++;
    }
}

$html = "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<style>
    body {
        font-family: 'Helvetica', sans-serif;
        font-size: 11px;
        color: #1A2B3C;
        margin: 30px;
    }
    .header {
        text-align: center;
        border-bottom: 3px solid #005A87;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }
    .header h1 {
        font-size: 16px;
        color: #005A87;
        margin: 0 0 4px 0;
    }
    .header p {
        font-size: 10px;
        color: #6C757D;
        margin: 0;
    }
    .info-examen {
        margin-bottom: 20px;
        width: 100%;
    }
    .info-examen td {
        padding: 4px 8px;
        font-size: 11px;
    }
    .info-examen .etiqueta {
        font-weight: bold;
        color: #005A87;
        width: 120px;
    }
    table.tabla-alumnos {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    table.tabla-alumnos th {
        background-color: #005A87;
        color: white;
        padding: 8px;
        text-align: left;
        font-size: 10px;
        text-transform: uppercase;
    }
    table.tabla-alumnos td {
        padding: 6px 8px;
        border-bottom: 1px solid #DEE2E6;
        font-size: 11px;
    }
    .centro { text-align: center; }
    .footer {
        margin-top: 30px;
        text-align: center;
        font-size: 9px;
        color: #6C757D;
    }
</style>
</head>
<body>

    <div class='header'>
        <h1>Sistema de Regularización Académica — SRA ESCOM</h1>
        <p>Reporte de Alumnos Registrados — Examen ETS</p>
    </div>

    <table class='info-examen'>
        <tr>
            <td class='etiqueta'>Materia:</td>
            <td>" . htmlspecialchars($examen['materia']) . " (" . htmlspecialchars($examen['carrera']) . ")</td>
            <td class='etiqueta'>Fecha:</td>
            <td>{$fecha}</td>
        </tr>
        <tr>
            <td class='etiqueta'>Turno:</td>
            <td>" . htmlspecialchars($examen['turno']) . "</td>
            <td class='etiqueta'>Horario:</td>
            <td>" . htmlspecialchars($examen['horario']) . "</td>
        </tr>
        <tr>
            <td class='etiqueta'>Salón:</td>
            <td>". htmlspecialchars($examen['edificio']) . htmlspecialchars($examen['salon']) . "</td>
            <td class='etiqueta'>Coordinador:</td>
            <td>" . htmlspecialchars($examen['coordNombre'] . ' ' . $examen['coordApellidoP']) . "</td>
        </tr>
        <tr>
            <td class='etiqueta'>Total alumnos:</td>
            <td>" . count($alumnos) . "</td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class='tabla-alumnos'>
        <thead>
            <tr>
                <th style='width:140px;'>Boleta</th>
                <th>Nombre completo</th>
            </tr>
        </thead>
        <tbody>
            {$filasAlumnos}
        </tbody>
    </table>
</body>
</html>
";

// GENERAR PDF

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', false);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();

// Nombre del archivo
$nombreArchivo = "reporte_examen_{$id_examen}.pdf";

// Forzar descarga
$dompdf->stream($nombreArchivo, ['Attachment' => true]);