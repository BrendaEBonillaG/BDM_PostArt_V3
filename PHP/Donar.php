<?php
session_start();
require __DIR__ . '/../Conexion.php';

// Validación básica de sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: Login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario_donante = $_SESSION['usuario']['ID_Usuario'];
    $id_donacion = intval($_POST['id_donacion']);
    $id_usuario_artista = intval($_POST['id_usuario_artista']);  // Necesitamos recibirlo en el formulario
    $monto = floatval($_POST['monto']);

    // Validación de monto mínimo
    if ($monto <= 0) {
        echo "El monto debe ser mayor a cero.";
        exit();
    }

    // Llamada al stored procedure
    $stmt = $conexion->prepare("CALL SP_InsertarDonador(?, ?, ?, ?)");
    $stmt->bind_param("iiid", $id_usuario_donante, $id_usuario_artista, $id_donacion, $monto);
    
    if ($stmt->execute()) {
        echo "<script>
            alert('Donación realizada con éxito.');
            window.location.href = '../proyecto.php?id={$id_donacion}';
        </script>";
    } else {
        echo "Error al registrar la donación.";
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Método inválido.";
}
?>
