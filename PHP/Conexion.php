<?php
$servidor = "localhost:3306";
$usuario = "root";
$contrasena = "Admin";
$basedatos = "PostArt";


$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
echo "Conexión exitosa";
?>