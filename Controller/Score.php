<?php
session_start();

require_once __DIR__ . '/../Model/ConexionBD.php';

header('Content-Type: text/plain; charset=utf-8');

if (!isset($_SESSION['nick'])) {
    http_response_code(401);
    echo 'Sesión no iniciada';
    exit;
}

$nick = $_SESSION['nick'];
$score = isset($_POST['numero']) ? intval($_POST['numero']) : 0;

if (!isset($conexion) || !$conexion) {
    http_response_code(500);
    echo 'Error de conexión';
    exit;
}

$stmt = $conexion->prepare('UPDATE usuario SET score = ? WHERE nick = ?');
if (!$stmt) {
    http_response_code(500);
    echo 'Error preparando consulta';
    exit;
}

$stmt->bind_param('is', $score, $nick);
$ok = $stmt->execute();
$stmt->close();
mysqli_close($conexion);

if ($ok) {
    echo 'Score guardado correctamente';
} else {
    http_response_code(500);
    echo 'Error al guardar score';
}
?>