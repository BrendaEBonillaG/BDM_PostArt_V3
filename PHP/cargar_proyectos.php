<?php
include("conexion.php");

$stmt = $conexion->prepare("
    SELECT d.Id_Donacion, d.Titulo, d.Contenido, d.Imagen, d.Fecha_Limite, u.Nickname AS Autor
    FROM Donaciones d
    JOIN Usuario u ON d.Id_usuario = u.ID_Usuario
    ORDER BY d.Fecha_Limite DESC
");
$stmt->execute();
$resultado = $stmt->get_result();

while ($row = $resultado->fetch_assoc()) {
    $imagen_base64 = base64_encode($row['Imagen']);
    $id = $row['Id_Donacion'];
    $titulo = htmlspecialchars($row['Titulo']);
    $descripcion = htmlspecialchars(substr($row['Contenido'], 0, 80)) . "...";
    $fecha = date("d M Y", strtotime($row['Fecha_Limite']));
    $autor = htmlspecialchars($row['Autor']);

    echo "
    <div class='card_proyecto'>
        <a href='proyecto.php?id=$id'>
            <div class='card_proyecto_img'>
                <img class='img_proyecto' src='data:image/jpeg;base64,$imagen_base64' alt=''>
            </div>
            <div class='card_proyect_text'>
                <p class='estado'>Proyecto</p>
                <p class='date'>Finaliza $fecha</p>
                <h1 class='titulo'>$titulo</h1>

                <div class='autor_cart_proyecto'>

                </div>
            </div>
        </a>
    </div>";
}

$stmt->close();
?>
