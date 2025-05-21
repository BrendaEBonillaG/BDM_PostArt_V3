<?php
require __DIR__ . '/../Conexion.php';


session_start();


$nombre = $_POST['nombre'];
$nickname = $_POST['nickname'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$rol = $_POST['rol'];

ini_set('memory_limit', '512M'); 

$stmt = $conexion->prepare("CALL VerificarCorreo(?, @existe)");
$stmt->bind_param("s", $correo);
$stmt->execute();

$result = $conexion->query("SELECT @existe AS existe");
$row = $result->fetch_assoc();

if ($row['existe'] > 0) {
 
    echo "<script>
        alert('Este correo ya está registrado. Por favor, use otro.');
        window.location.href = '../Login.html';
    </script>";
    exit(); 
}


$foto = null;
if (isset($_FILES['foto']['tmp_name']) && $_FILES['foto']['tmp_name'] != "") {

    $foto = fopen($_FILES['foto']['tmp_name'], 'rb');  
}


$stmt = $conexion->prepare("CALL RegistrarUsuario(?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssb", $nombre, $nickname, $correo, $contrasena, $rol, $foto);

if ($stmt->execute()) {

    $_SESSION['usuario'] = [
        'nombre' => $nombre,
        'nickname' => $nickname,
        'correo' => $correo,
        'rol' => $rol
    ];

    echo "<script>
        alert('Registro exitoso.');
        window.location.href = '../index.php'; // Puedes cambiar esta URL a la página de inicio que desees
    </script>";
} else {
    echo "Error al registrar: " . $stmt->error;

    echo "Error MySQL: " . mysqli_error($conexion);
}


if ($foto) {
    fclose($foto);
}

$stmt->close();
$conexion->close();
?>
