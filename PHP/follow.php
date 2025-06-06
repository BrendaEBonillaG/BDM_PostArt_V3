<?php
session_start();
require __DIR__ . '/../Conexion.php';

if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    exit('No autorizado');
}

$idUsuario = $_SESSION['usuario']['ID_Usuario'];
$idArtista = isset($_POST['artista_id']) ? intval($_POST['artista_id']) : 0;

if ($idUsuario === $idArtista || $idArtista <= 0) {
    exit('error');
}

// Llamada al procedimiento almacenado
$stmt = $conexion->prepare("CALL SeguirArtista(?, ?)");
$stmt->bind_param("ii", $idUsuario, $idArtista);
$stmt->execute();

$resultado = $stmt->get_result()->fetch_assoc()['resultado'];

echo $resultado;
