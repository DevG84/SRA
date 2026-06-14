<?php
header("Content-Type: application/json; charset=utf-8");

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/db.php';

// Cargar variables del .env
$env = parse_ini_file(__DIR__ . '/../../.env');

// Cargar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../../vendor/autoload.php';

$correo = $_POST['correo'] ?? '';

if (empty($correo)) {
    echo json_encode(['cod' => 0, 'msj' => 'Falta el correo', 'icono' => 'error']);
    exit;
}

$respAX = [];

try {
    $conn = (new Connection)->connect();

    // Buscar en coordinador
    $stmt = $conn->prepare("SELECT id_coordinador, nombre FROM coordinador WHERE correo = :correo");
    $stmt->execute([':correo' => $correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $tabla = 'coordinador';
    $campoId = 'id_coordinador';

    // Si no está en coordinador buscar en gestion
    if (!$usuario) {
        $stmt = $conn->prepare("SELECT id_gestion, nombre FROM gestion WHERE correo = :correo");
        $stmt->execute([':correo' => $correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $tabla = 'gestion';
        $campoId = 'id_gestion';
    }

    if (!$usuario) {
        echo json_encode(['cod' => 0, 'msj' => 'No existe una cuenta con ese correo', 'icono' => 'error']);
        exit;
    }

    // Generar contraseña aleatoria de 10 caracteres
    $nuevaContrasena = bin2hex(random_bytes(5)); // ej. a3f9b2c1d4

    // Hashear y actualizar en BD
    $hash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);
    $idUsuario = $tabla === 'coordinador' ? $usuario['id_coordinador'] : $usuario['id_gestion'];
    $stmt = $conn->prepare("UPDATE $tabla SET contrasena = :hash WHERE $campoId = :id");
    $stmt->execute([':hash' => $hash, ':id' => $idUsuario]);

    // Enviar correo con PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $env['MAIL_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $env['MAIL_USER'];
    $mail->Password   = $env['MAIL_PASS'];
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $env['MAIL_PORT'];
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($env['MAIL_FROM'], $env['MAIL_FROM_NAME']);
    $mail->addAddress($correo, $usuario['nombre']);
    $mail->Subject = 'Recuperación de contraseña — SRA ESCOM';
    $mail->Body    = "Hola {$usuario['nombre']},\n\nTu nueva contraseña es: $nuevaContrasena\n\nTe recomendamos cambiarla al iniciar sesión.\n\n— SRA ESCOM";

    $mail->send();

    $respAX['cod']   = 1;
    $respAX['msj']   = "Se envió la nueva contraseña a $correo";
    $respAX['icono'] = "success";

} catch (Exception $e) {
    error_log("Error en RecuperarContrasenaController: " . $e->getMessage());
    $respAX['cod']   = 0;
    $respAX['msj']   = "Error al procesar la solicitud";
    $respAX['icono'] = "error";
}

echo json_encode($respAX);