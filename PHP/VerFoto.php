<?php
require __DIR__ . '../Conexion.php';

// Ejemplo: obtener datos del usuario con ID 1
$id = 1;
$stmt = $conexion->prepare("SELECT Foto_perfil FROM Usuario WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($user = $resultado->fetch_assoc()) {
    if (!empty($user['Foto_perfil'])) {
        $imagenBlob = $user['Foto_perfil'];
        $imagenBase64 = base64_encode($imagenBlob);
        echo '<img src="data:image/jpeg;base64,' . $imagenBase64 . '" alt="Foto de perfil" class="profile-img">';
    } else {
        echo '<img src="../imagenes-prueba/User.jpg" alt="Foto por defecto" class="profile-img">';
    }
} else {
    echo 'Usuario no encontrado.';
}

$stmt->close();
$conexion->close();
?>
