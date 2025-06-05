<?php include ('conexion.php');?>
<?php
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
$fotoContenido = null;
$tieneFoto = false;

if (isset($_FILES['foto']['tmp_name']) && $_FILES['foto']['tmp_name'] != "") {
    $fotoContenido = file_get_contents($_FILES['foto']['tmp_name']);
    $tieneFoto = true;
}

// Registrar usuario
$stmt = $conexion->prepare("CALL RegistrarUsuario(?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Error en prepare: " . $conexion->error);
}

// Como no se puede pasar directamente un blob con bind_param en procedimientos, usamos null y luego send_long_data
$fotoNull = NULL;

$stmt->bind_param("sssss" . "b", $nombre, $nickname, $correo, $contrasena, $rol, $fotoNull);

if ($tieneFoto) {
    $stmt->send_long_data(5, $fotoContenido); // el índice es cero-based (empieza desde 0)
}

if ($stmt->execute()) {
    echo "<script>
        alert('Registro exitoso.');
        window.location.href = '../Login.php';
    </script>";
} else {
    echo "Error al registrar: " . $stmt->error;
    echo "Error MySQL: " . mysqli_error($conexion);
}

$stmt->close();
$conexion->close();
?>
