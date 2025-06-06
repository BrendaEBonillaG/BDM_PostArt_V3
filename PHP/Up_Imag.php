<?php 
include ('../Conexion.php');
session_start();

if ($_POST["dato"] == 'inserta_archivo') {
    $usuario = $_SESSION['usuario']['ID_Usuario'];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $tipo = $_POST["tipo"];
    $catego = $_POST["categoria"] ?? 1;

    if (!empty($_FILES['imagen']['tmp_name'])) {
        $archivo = $_FILES['imagen'];
        $contenidoImagen = file_get_contents($archivo['tmp_name']);

        $stmt = $conexion->prepare("CALL SP_InsertarPublicacion(?, ?, ?, ?, ?, ?)");

        $null = null;
        $stmt->bind_param("iisssb", $usuario, $catego, $titulo, $descripcion, $contenidoImagen, $tipo);
        $stmt->send_long_data(4, $contenidoImagen); 

        if ($stmt->execute()) {
            echo "Imagen subida correctamente.";
        } else {
            echo "Error al subir la imagen: " . $stmt->error;
        }

        $stmt->close();
        while ($conexion->more_results() && $conexion->next_result()) {
            $conexion->use_result();
        }
    } else {
        echo 'No se ha seleccionado ninguna imagen';
    }
} else {
    echo 'No se ha enviado el formulario correctamente';
}
?>
