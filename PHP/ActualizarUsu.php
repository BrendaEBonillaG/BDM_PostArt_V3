<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../Login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

require_once __DIR__ . '/../Conexion.php'; 

$nombre = $_POST['name'] ?? '';
$apellido1 = $_POST['surname1'] ?? '';
$apellido2 = $_POST['surname2'] ?? '';
$correo = $_POST['email'] ?? '';
$biografia = $_POST['description'] ?? '';
$foto = null;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto = file_get_contents($_FILES['foto']['tmp_name']);
}

$stmt = $conexion->prepare("CALL UpdateUserProfileInfo(?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    die('Error en la preparación: ' . $conexion->error);
}

$stmt->bind_param("issssss", $user_id, $nombre, $apellido1, $apellido2, $correo, $biografia, $foto);

if ($stmt->execute()) {

    header("Location: perfil.php"); 
} else {
    echo "Error al guardar los cambios: " . $stmt->error;
}


$stmt->close();
$conexion->close();
?>
