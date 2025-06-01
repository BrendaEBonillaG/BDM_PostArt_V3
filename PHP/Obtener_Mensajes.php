<?php
require '../Conexion.php';
session_start();

if (!isset($_SESSION['usuario']['ID_Usuario']) || !isset($_GET['id_chat'])) {
    http_response_code(400);
    exit('Faltan datos');
}

$id_usuario = $_SESSION['usuario']['ID_Usuario'];
$id_chat = intval($_GET['id_chat']);

// Ajuste: columna correcta es id_chat_Privado
$stmt = $conexion->prepare("SELECT id_usuario, contenido, fecha_envio 
                            FROM Mensajes_Privado 
                            WHERE id_chat_Privado = ? 
                            ORDER BY fecha_envio ASC");
$stmt->bind_param("i", $id_chat);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $clase = ($row['id_usuario'] == $id_usuario) ? 'message-sent' : 'message-received';

    echo '<div class="' . $clase . '">';
    echo '<div class="text-received"><p>' . htmlspecialchars($row['contenido']) . '</p></div>';
    echo '<span class="message-time">' . date("H:i", strtotime($row['fecha_envio'])) . '</span>';
    echo '</div>';
}
?>
