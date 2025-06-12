<?php
session_start();
require __DIR__ . '/../Conexion.php';

// Validamos que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    echo "Usuario no autenticado";
    exit;
}

// Obtenemos los valores POST (vienen de FormData)
$id_usuario_donante = $_SESSION['usuario']['ID_Usuario'];
$id_donacion = intval($_POST["id_donacion"] ?? 0);
$id_usuario_artista = intval($_POST["id_usuario_artista"] ?? 0);
$monto = floatval($_POST["monto"] ?? 0);

// Validación básica de los datos
if ($id_donacion <= 0 || $id_usuario_artista <= 0 || $monto <= 0) {
    echo "Datos inválidos.";
    exit;
}

try {
    $stmt = $conexion->prepare("CALL SP_InsertarDonacionCompleta(?, ?, ?, ?)");
    $stmt->bind_param("iiid", $id_usuario_donante, $id_usuario_artista, $id_donacion, $monto);
    $stmt->execute();

    echo "Donación registrada correctamente";
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}

$stmt->close();
$conexion->close();
?>
