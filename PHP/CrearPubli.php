<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo "No hay usuario en sesión";
    var_dump($_SESSION);
    exit;
}
if (!isset($_SESSION['usuario']['ID_Usuario'])) {
    echo "El usuario en sesión no tiene 'id'";
    var_dump($_SESSION['usuario']);
    exit;
}
echo "Usuario en sesión: " . $_SESSION['usuario']['ID_Usuario'];


require __DIR__ . '/../Conexion.php'; 

// Obtener datos del formulario
$titulo = $_POST['titulo'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$contenido = $_POST['contenido'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$usuarioId = $_SESSION['usuario']['ID_Usuario']; // Asumo que tienes id del usuario en sesión

// Procesar la imagen subida
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $imgTemp = $_FILES['imagen']['tmp_name'];
    $imgName = $_FILES['imagen']['name'];
    $imgData = file_get_contents($imgTemp); // Imagen en binario
} else {
    die("Error al subir la imagen.");
}

// Preparar y ejecutar el procedimiento almacenado
$stmt = $conexion->prepare("CALL InsertarPublicacion(?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Error en la preparación: " . $conexion->error);
}

// Asumiendo que InsertarPublicacion recibe (titulo, categoria, contenido, tipo, usuarioId, imagen blob)
$stmt->bind_param(
    "sissib", // s = string, i = int, b = blob
    $titulo,
    $categoria,
    $contenido,
    $tipo,
    $usuarioId,
    $null
);

// Para enviar blob con mysqli hay que usar send_long_data
// Pero primero asignamos null a la variable que enviaremos y enviaremos el blob con send_long_data

$null = NULL;
$stmt->bind_param("sissib", $titulo, $categoria, $contenido, $tipo, $usuarioId, $null);

$stmt->send_long_data(5, $imgData);

if ($stmt->execute()) {
    echo "Publicación insertada con éxito.";
} else {
    echo "Error al insertar publicación: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
