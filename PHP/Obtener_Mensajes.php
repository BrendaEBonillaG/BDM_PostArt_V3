<?php
require '../Conexion.php';
session_start();

if (
    !isset($_SESSION['usuario']['ID_Usuario']) ||
    !isset($_GET['id_chat']) ||
    !isset($_GET['tipo'])
) {
    http_response_code(400);
    exit('Faltan datos');
}

$id_usuario = (int)$_SESSION['usuario']['ID_Usuario'];
$id_chat = (int)$_GET['id_chat'];
$tipo = $_GET['tipo'];

if ($tipo === 'privado') {
    $stmt = $conexion->prepare("CALL ChatPrivado_Operacion('listar', ?, 0, NULL)");
    $stmt->bind_param("i", $id_chat);
} elseif ($tipo === 'grupal') {
    $stmt = $conexion->prepare("CALL ChatGrupal_Operacion('listar', ?, 0, NULL)");
    $stmt->bind_param("i", $id_chat);
} else {
    http_response_code(400);
    exit('Tipo de chat inválido');
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $clase = ($row['id_usuario'] == $id_usuario) ? 'message-sent' : 'message-received';

    echo '<div class="' . $clase . '">';
    echo '<div class="text-received"><p>' . htmlspecialchars($row['contenido']) . '</p></div>';
    echo '<span class="message-time">' . date("H:i", strtotime($row['fecha_envio'])) . '</span>';
    echo '</div>';
}

$stmt->close();
$conexion->close();
?>
