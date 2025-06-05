<?php 
include ('../Conexion.php');
session_start(); // Ensure session is started

if ($_POST["dato"] == 'inserta_archivo') {
    $usuario = $_SESSION['usuario']['ID_Usuario']; // User ID
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $tipo = $_POST["tipo"];
    $catego = $_POST["categoria"] ?? 1; // Default category if none selected

    if (!empty($_FILES['imagen']['tmp_name'])) {
        $archivo = $_FILES['imagen'];
        $contenidoImagen = file_get_contents($archivo['tmp_name']);

        $sql = "INSERT INTO Publicaciones (Id_usuario, Id_Categoria, Titulo, Contenido, Imagen, Tipo, Fecha_creacion, Estado) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Activo')";
        $stmt = $conexion->prepare($sql);

        $stmt->bind_param("iisssb", $usuario, $catego, $titulo, $descripcion, $contenidoImagen, $tipo);
        $stmt->send_long_data(4, $contenidoImagen); // Ensure correct index for BLOB
        
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