<?php
session_start();
include("../Conexion.php");

if (!isset($conexion)) {
    die("Error: No se estableció la conexión con la base de datos.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_SESSION['usuario']['ID_Usuario'];
    $id_categoria = intval($_POST['categoria']);
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

    $stmt = $conexion->prepare("CALL SP_InsertarProyecto(?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "iissssds",
        $id_usuario,
        $id_categoria,
        $titulo,
        $descripcion,
        $imagen_binario,
        $video_url,
        $meta,
        $fecha_limite
    );

    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $id_proyecto = $fila['id_proyecto'];

        echo "<script>
            alert('Proyecto guardado exitosamente.');
            window.location.href = '../proyecto.php?id=$id_proyecto';
        </script>";
    } else {
        echo "<script>alert('Error al guardar el proyecto.');</script>";
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Método no permitido.";
}
