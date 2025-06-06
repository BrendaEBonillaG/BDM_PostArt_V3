<?php
session_start();
require '../config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Index.php");
    exit();
}

$id_emisor = $_SESSION['usuario']['id'] ?? null;

if ($id_emisor === null) {
    die("No se pudo obtener el ID del emisor");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de usuario inválido");
}

$id_remitente = intval($_GET['id']);

if ($id_emisor === $id_remitente) {
    die("No puedes chatear contigo mismo");
}

// Paso 1: Verificar si ya existe un chat privado
$stmt_check = $conn->prepare("CALL SP_ObtenerChatPrivado(:rem1, :em1, :rem2, :em2)");
$stmt_check->execute([
    ':rem1' => $id_remitente,
    ':em1' => $id_emisor,
    ':rem2' => $id_emisor,
    ':em2' => $id_remitente
]);
$result = $stmt_check->fetch(PDO::FETCH_ASSOC);
$stmt_check->closeCursor();

if ($result && isset($result['id_chat'])) {
    $id_chat = $result['id_chat'];
} else {
    // Paso 2: Crear nuevo chat si no existe
    $stmt_insert = $conn->prepare("CALL SP_CrearChatPrivado(:remitente, :emisor)");
    $stmt_insert->execute([
        ':remitente' => $id_remitente,
        ':emisor' => $id_emisor
    ]);
    $stmt_insert->closeCursor();

    // Paso 3: Obtener el último chat creado (alternativamente puedes devolverlo desde el procedure)
    $stmt_last_id = $conn->query("SELECT LAST_INSERT_ID() AS id_chat");
    $id_chat = $stmt_last_id->fetch(PDO::FETCH_ASSOC)['id_chat'];
}

header("Location: ../Chat.php?id=" . $id_chat);
exit();
?>
