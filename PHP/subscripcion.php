<?php
session_start();
require __DIR__ . '/../Conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['status' => 'no_autorizado']);
    exit();
}

$idComprador = $_SESSION['usuario']['ID_Usuario'];
$idArtista = isset($_POST['artista_id']) ? intval($_POST['artista_id']) : 0;
$monto = 10.00;  // Puedes establecerlo dinámico si después lo deseas

if ($idComprador === $idArtista || $idArtista <= 0) {
    echo json_encode(['status' => 'error_mismo_usuario']);
    exit();
}

// Primero verificamos si ya existe subscripción activa
$stmtCheck = $conexion->prepare("
    SELECT Estado FROM Subscripciones 
    WHERE Id_usuario_comprador = ? AND Id_usuario_artista = ? LIMIT 1
");
$stmtCheck->bind_param("ii", $idComprador, $idArtista);
$stmtCheck->execute();
$resCheck = $stmtCheck->get_result();

if ($resCheck->num_rows > 0) {
    $fila = $resCheck->fetch_assoc();
    if ($fila['Estado'] === 'Activa') {
        echo json_encode(['status' => 'ya_suscrito']);
        $resCheck->close();
        $stmtCheck->close();
        exit();
    } else {
        // Si estaba cancelada, la reactivamos
        $resCheck->close();
        $stmtCheck->close();

        $stmtUpdate = $conexion->prepare("
            UPDATE Subscripciones SET Estado = 'Activa', Fecha_inicio = CURRENT_TIMESTAMP
            WHERE Id_usuario_comprador = ? AND Id_usuario_artista = ?
        ");
        $stmtUpdate->bind_param("ii", $idComprador, $idArtista);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        echo json_encode(['status' => 'reactivado']);
        exit();
    }
}

$resCheck->close();
$stmtCheck->close();

// Si no existía registro, insertamos uno nuevo
$stmtInsert = $conexion->prepare("
    INSERT INTO Subscripciones (Id_usuario_comprador, Id_usuario_artista, Monto)
    VALUES (?, ?, ?)
");
$stmtInsert->bind_param("iid", $idComprador, $idArtista, $monto);
$stmtInsert->execute();
$stmtInsert->close();

echo json_encode(['status' => 'suscrito']);
exit();
?>
