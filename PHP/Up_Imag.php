<?php 
include ('../Conexion.php');
session_start(); // Asegúrate de tener la sesión iniciada

if ($_POST["dato"] == 'inserta_archivo') {
    $usuario = $_SESSION['usuario']['ID_Usuario']; // accede correctamente al ID del usuario
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $tipo = $_POST["tipo"];
    $catego = $_POST["categoria"] ?? 1; // Asegúrate de recibirlo o pon un valor por defecto

    if (!empty($_FILES['imagen']['tmp_name'])) {
        $archivo = $_FILES['imagen'];
        $contenidoImagen = file_get_contents($archivo['tmp_name']);

        $sql = "INSERT INTO Publicaciones (Id_usuario, Id_Categoria, Titulo, Contenido, Imagen, Tipo) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        $stmt->bind_param("iissbs", $usuario, $catego, $titulo, $descripcion, $contenidoImagen, $tipo);

        if ($stmt->execute()) {
            echo "Imagen subida correctamente.";
        } else {
            echo "Error al subir la imagen: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo 'No se ha seleccionado ninguna imagen';
    }
} else {
    echo 'No se ha enviado el formulario correctamente';
}
?>
