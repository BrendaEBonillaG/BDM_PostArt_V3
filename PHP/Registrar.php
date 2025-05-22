<?php
require __DIR__ . '/../Conexion.php';
session_start();

$nombre = $_POST['nombre'];
$nickname = $_POST['nickname'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$rol = $_POST['rol'];

// Validar correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
        alert('Correo electrónico no válido.');
        window.location.href = '../Login.html';
    </script>";
    exit();
}

// Validar contraseña: mínimo 8 caracteres, al menos una letra y un número
if (strlen($contrasena) < 8 || !preg_match('/[A-Za-z]/', $contrasena) || !preg_match('/[0-9]/', $contrasena)) {
    echo "<script>
        alert('La contraseña debe tener al menos 8 caracteres, incluir letras y números.');
        window.location.href = '../Login.html';
    </script>";
    exit();
}

// Verificar si el correo ya está registrado
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

// Procesar imagen de perfil si existe
$foto = null;
if (isset($_FILES['foto']['tmp_name']) && $_FILES['foto']['tmp_name'] != "") {
    $foto = fopen($_FILES['foto']['tmp_name'], 'rb');
}

// Registrar usuario
$stmt = $conexion->prepare("CALL RegistrarUsuario(?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssb", $nombre, $nickname, $correo, $contrasena, $rol, $foto);

if ($stmt->execute()) {
    $_SESSION['usuario'] = [
        'id' => $id_usuario,
        'nombre' => $nombre,
        'nickname' => $nickname,
        'correo' => $correo,
        'rol' => $rol
    ];

    echo "<script>
        alert('Registro exitoso.');
        window.location.href = '../index.php';
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
