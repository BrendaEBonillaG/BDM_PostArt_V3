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
    exit('error_mismo_usuario');
}

// Llamada al procedimiento almacenado
$stmt = $conexion->prepare("CALL SeguirArtista(?, ?)");
$stmt->bind_param("ii", $idUsuario, $idArtista);
$stmt->execute();

$resultado = '';
if ($res = $stmt->get_result()) {
    if ($row = $res->fetch_assoc()) {
        $resultado = $row['resultado'];
    }
    $res->close();
} else {
    $resultado = 'error_procedure';
}

$stmt->close();

// Limpieza de posibles resultados pendientes (importantísimo en procedures)
while ($conexion->more_results() && $conexion->next_result()) {
    $extra = $conexion->use_result();
    if ($extra instanceof mysqli_result) {
        $extra->free();
    }
}

echo $resultado;
