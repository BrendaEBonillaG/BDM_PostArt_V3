<?php
session_start();
require __DIR__ . '/../Conexion.php';

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo "No autorizado";
    exit;
}

$id_usuario = $_SESSION['usuario']['ID_Usuario'];
$id_publicacion = intval($_POST['id_publicacion'] ?? 0);

if ($id_publicacion <= 0) {
    http_response_code(400);
    echo "ID de publicación inválido";
    exit;
}

try {
    $stmt = $conexion->prepare("CALL SP_InsertarLike(?, ?)");
    $stmt->bind_param("ii", $id_usuario, $id_publicacion);
    $stmt->execute();

    echo "Like registrado correctamente";

    $stmt->close();
    $conexion->close();
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo "Error al registrar el like: " . $e->getMessage();
}
?>
