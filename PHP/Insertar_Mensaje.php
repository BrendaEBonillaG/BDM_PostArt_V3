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
    !isset($_POST['id_chat'])
) {
    http_response_code(400);
    echo "Faltan datos.";
    exit;
}

$id_usuario = (int)$_SESSION['usuario']['ID_Usuario'];
$mensaje = trim($_POST['mensaje']);
$id_chat = (int)$_POST['id_chat'];

if ($mensaje === '') {
    echo "Mensaje vacío.";
    exit;
}

// Insertar en Mensajes_Privado
$stmt = $conexion->prepare("INSERT INTO Mensajes_Privado (id_chat_Privado, id_usuario, contenido) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $id_chat, $id_usuario, $mensaje);

if ($stmt->execute()) {
    echo "Mensaje enviado correctamente.";
} else {
    echo "Error al enviar el mensaje: " . $stmt->error;
}
?>
