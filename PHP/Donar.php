<?php
session_start();
require __DIR__ . '/../Conexion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: Login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario_donante = $_SESSION['usuario']['ID_Usuario'];
    $id_donacion = intval($_POST['id_donacion']);
    $id_usuario_artista = intval($_POST['id_usuario_artista']);
    $monto = floatval($_POST['monto']);

    try {
        $stmt = $conexion->prepare("CALL SP_InsertarDonacionCompleta(?, ?, ?, ?)");
        $stmt->bind_param("iiid", $id_usuario_donante, $id_usuario_artista, $id_donacion, $monto);
        $stmt->execute();

        echo "<script>
            alert('Donación realizada con éxito.');
            window.location.href = '../proyecto.php?id={$id_donacion}';
        </script>";
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); history.back();</script>";
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Método inválido.";
}
?>
