<?php
session_start();
require __DIR__ . '/../Conexion.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    echo json_encode(['status' => 'no_autorizado']);
    exit();
}

$idUsuario = $_SESSION['usuario']['ID_Usuario'];
$idArtista = isset($_POST['artista_id']) ? intval($_POST['artista_id']) : 0;

if ($idUsuario === $idArtista || $idArtista <= 0) {
    echo json_encode(['status' => 'ya_se_sigue']);
    exit();
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

while ($conexion->more_results() && $conexion->next_result()) {
    $extra = $conexion->use_result();
    if ($extra instanceof mysqli_result) {
        $extra->free();
    }
}

echo json_encode(['status' => strtolower(trim($resultado))]);
?>
