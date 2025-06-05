<?php
$servidor = "localhost:3306";
$usuario = "root";
$contrasena = "";
$basedatos = "PostArt1";


$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
echo "Conexión exitosa";
?>