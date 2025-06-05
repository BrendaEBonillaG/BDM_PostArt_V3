<?php
session_start();
include("../Conexion.php");

if (!isset($conexion)) {
    die("Error: No se estableció la conexión con la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_SESSION['usuario']['ID_Usuario'];
    $categoria = $_POST['categoria'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $video_url = $_POST['video_url'];
    $meta = floatval($_POST['meta']);
    $fecha_limite = $_POST['fecha_limite'];

    // Procesar imagen
    $imagen_binario = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name'] !== '') {
        $imagen_binario = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    $stmt = $conexion->prepare("CALL SP_InsertarDonacion(?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("isssssss",

        $id_usuario, 
        $categoria, 
        $titulo, 
        $descripcion, 
        $imagen_binario, 
        $video_url, 
        $meta, 
        $fecha_limite
    );

    if ($stmt->execute()) {
        echo "<script>alert('Proyecto guardado exitosamente.'); window.location.href='../proyecto.html';</script>";
    } else {
        echo "<script>alert('Error al guardar proyecto.');</script>";
    }

    $stmt->close();
    $conexion->close();

} else {
    echo "Método no permitido.";
}
