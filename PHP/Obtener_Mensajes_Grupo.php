<?php
require '../Conexion.php';
session_start();

if (!isset($_GET['id_grupo']) || !isset($_SESSION['usuario']['ID_Usuario'])) {
    exit('ID de grupo o sesión no válida');
}

$id_grupo = intval($_GET['id_grupo']);
$id_usuario_actual = $_SESSION['usuario']['ID_Usuario'];

$stmt = $conexion->prepare("
    SELECT m.contenido, m.fecha_envio, m.id_usuario, u.Nickname AS nombre_usuario
    FROM Mensajes_Grupales m
    INNER JOIN Usuario u ON m.id_usuario = u.ID_Usuario
    WHERE m.id_chat_Grupal = ?
    ORDER BY m.fecha_envio ASC
");

$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $esMio = $row['id_usuario'] == $id_usuario_actual;
    $clase = $esMio ? 'mensaje-propio' : 'mensaje-otro';

    echo '<div class="mensaje ' . $clase . '">';
    if (!$esMio) {
        echo '<strong>' . htmlspecialchars($row['nombre_usuario']) . ':</strong><br>';
    }
    echo '<span>' . htmlspecialchars($row['contenido']) . '</span>';
    echo '<br><small>' . htmlspecialchars($row['fecha_envio']) . '</small>';
    echo '</div>';
}

$stmt->close();
$conexion->close();
?>
