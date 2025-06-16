<?php
session_start();
require __DIR__ . '/../Conexion.php';  // ← conexión mysqli

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Index.php");
    exit();
}

$id_emisor = $_SESSION['usuario']['ID_Usuario'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de usuario inválido");
}

$id_remitente = intval($_GET['id']);

if ($id_emisor === $id_remitente) {
    die("No puedes chatear contigo mismo");
}

// Verificamos si ya existe chat privado
$stmt_check = $conexion->prepare("CALL SP_ObtenerChatPrivado(?, ?, ?, ?)");
$stmt_check->bind_param("iiii", $id_remitente, $id_emisor, $id_emisor, $id_remitente);
$stmt_check->execute();
$res_check = $stmt_check->get_result();

if ($row = $res_check->fetch_assoc()) {
    $id_chat = $row['id_chat'];
    $res_check->free();
    $stmt_check->close();
} else {
    $res_check->free();
    $stmt_check->close();

    while ($conexion->more_results() && $conexion->next_result()) { }

    $stmt_insert = $conexion->prepare("CALL SP_CrearChatPrivado(?, ?)");
    $stmt_insert->bind_param("ii", $id_remitente, $id_emisor);
    $stmt_insert->execute();
    $stmt_insert->close();

    while ($conexion->more_results() && $conexion->next_result()) { }

    $result_last = $conexion->query("SELECT LAST_INSERT_ID() AS id_chat");
    $row_last = $result_last->fetch_assoc();
    $id_chat = $row_last['id_chat'];
}

header("Location: ../Chat.php?id=" . $id_chat);
exit();
?>
