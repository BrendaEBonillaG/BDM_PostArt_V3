<?php 
include('conexion.php');

if ($_POST["dato"] == 'inserta_archivo') {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $tipo = $_POST["tipo"];

    if (!empty($_FILES['imagen']['tmp_name'])) {
        $archivo = $_FILES['imagen']['tmp_name'];
        $contenidoImagen = file_get_contents($archivo);

        // Consulta SQL
        $sql = "INSERT INTO Publicaciones (Titulo, Contenido, Imagen, Tipo) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        
        $stmt->bind_param("ssbs", $titulo, $descripcion, $contenidoImagen, $tipo);


        $stmt = $conexion->prepare($sql);
        $null = NULL;
        $stmt->bind_param("ssbs", $titulo, $descripcion, $null, $tipo);
        $stmt->send_long_data(2, $contenidoImagen); 

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
