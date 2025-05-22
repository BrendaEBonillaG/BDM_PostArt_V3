<?php
session_start(); 

require __DIR__ . '/../Conexion.php'; 

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conexion->prepare("CALL LoginUsuario(?, ?)");
$stmt->bind_param("ss", $username, $password);

$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();
   $_SESSION['usuario'] = [
    'ID_Usuario' => $user['ID_Usuario'],
    'Nickname' => $user['Nickname'],
    'Rol' => $user['Rol']
];


    header('Location: ../index.php');
    exit();
} else {
   
    echo "<script>
            alert('Credenciales incorrectas, por favor intente de nuevo.');
            window.location.href = '../Login.html';
          </script>";
}


$stmt->close();
$conexion->close();
?>
