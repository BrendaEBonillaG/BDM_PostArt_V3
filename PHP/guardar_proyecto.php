<?php
require '../Conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: Index.php");
  exit();
}

$id_usuario = $_SESSION['usuario']['ID_Usuario'];

$titulo = $_POST['titulo'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$meta = $_POST['meta'] ?? '';
$fecha_limite = $_POST['fecha_limite'] ?? '';

// ---------- 1. Imagen como BLOB ----------
$imagenBinaria = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $imagenBinaria = file_get_contents($_FILES['imagen']['tmp_name']);
}

// ---------- 2. Guardar video en carpeta y obtener path ----------
$videoRutaRelativa = null;

if (isset($_FILES['video_url']) && $_FILES['video_url']['error'] === 0) {
    $carpetaVideos = __DIR__ . '/../videos/';
    if (!file_exists($carpetaVideos)) {
        mkdir($carpetaVideos, 0777, true);
    }

    $nombreOriginal = basename($_FILES['video_url']['name']);
    $nombreUnico = uniqid() . '_' . $nombreOriginal;
    $rutaCompleta = $carpetaVideos . $nombreUnico;

    if (move_uploaded_file($_FILES['video_url']['tmp_name'], $rutaCompleta)) {
        $videoRutaRelativa = 'videos/' . $nombreUnico;
    } else {
        die("Error al guardar el video.");
    }
}

// ---------- 3. Llamar al Stored Procedure ----------
$stmt = $conexion->prepare("CALL SP_InsertarDonacion(?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
    "iissssds", // Tipos: i=int, s=string, d=double
    $id_usuario,
    $categoria,
    $titulo,
    $descripcion,
    $imagenBinaria,
    $videoRutaRelativa,
    $meta,
    $fecha_limite
);

if ($stmt->execute()) {
    echo "Proyecto guardado correctamente usando el procedimiento almacenado.";
} else {
    echo "Error al guardar el proyecto: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
