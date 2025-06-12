<?php
header('Content-Type: application/json');
require __DIR__ . '/../Conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["error" => "ID inválido"]);
    exit;
}

$idProyecto = intval($_GET['id']);

// Ejecutamos el SP que ya usas para obtener resumen
$stmt = $conexion->prepare("CALL SP_ObtenerResumenDonacion(?)");
$stmt->bind_param("i", $idProyecto);
$stmt->execute();

$res = $stmt->get_result();
$row = $res->fetch_assoc();

$recaudado = $row['Recaudado'] ?? 0;
$participantes = $row['Participantes'] ?? 0;

$stmt->close();
$conexion->close();

echo json_encode([
    "recaudado" => $recaudado,
    "participantes" => $participantes
]);
?>
