<?php
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    // Ojo, la clave en el array es 'Nickname' (mayúscula N)
    // echo "Bienvenido, " . $usuario['Nickname'];
    header('Location: PHP/Dashboard.php');
} else {
    // No imprimir nada antes del header
    header('Location: Login.html');
    exit();
}
?>


