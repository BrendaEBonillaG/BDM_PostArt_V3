<?php 
include ('conexion.php');
// Subir imagenes
if ($_POST["dato"] == 'inserta_archivo') {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $tipo = $_POST["tipo"];
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $archivo = $_FILES['imagen'];
        
        // Leer el contenido de la imagen
        $contenidoImagen = file_get_contents($archivo['tmp_name']);
        
        // Consulta SQL
        $sql = "INSERT INTO Publicaciones (Titulo, Contenido, Imagen, Tipo) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        
        // Enlazar datos
        $stmt->bind_param("ssbs", $titulo, $descripcion, $contenidoImagen, $tipo);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Imagen subida correctamente.";
        } else {
            echo "Error al subir la imagen: " . $stmt->error;
        }
        
        // Cerrar la declaración
        $stmt->close();
    } else {
        echo 'No se ha seleccionado ninguna imagen';
    }
} else {
    echo 'No se ha enviado el formulario correctamente';
}
?>