<?php
$servername = "localhost:3306";
$username = "root"; 
$password = ""; 
$database = "PostArt";

$conexion = new mysqli($servername, $username, $password, $database);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>