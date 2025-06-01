<?php
require '../Conexion.php';

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validar que existan los datos
if (
    !isset($_SESSION['usuario']['ID_Usuario']) ||
    !isset($_POST['mensaje']) ||
    !isset($_POST['id_chat']) ||
    !isset($_POST['tipo']) // 'privado' o 'grupal'
) {
    http_response_code(400);
    echo "Faltan datos.";
    exit;
}

$id_usuario = (int)$_SESSION['usuario']['ID_Usuario'];
$mensaje = trim($_POST['mensaje']);
$id_chat = (int)$_POST['id_chat'];
$tipo = $_POST['tipo'];

if ($mensaje === '') {
    echo "Mensaje vacío.";
    exit;
}

// Lógica para determinar el procedimiento según el tipo de chat
if ($tipo === 'privado') {
    $stmt = $conexion->prepare("CALL ChatPrivado_Operacion('insertar', ?, ?, ?)");
} elseif ($tipo === 'grupal') {
    $stmt = $conexion->prepare("CALL ChatGrupal_Operacion('insertar', ?, ?, ?)");
} else {
    http_response_code(400);
    echo "Tipo de chat no válido.";
    exit;
}

$stmt->bind_param("iis", $id_chat, $id_usuario, $mensaje);

if ($stmt->execute()) {
    echo "Mensaje enviado correctamente.";
} else {
    echo "Error al enviar el mensaje: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
