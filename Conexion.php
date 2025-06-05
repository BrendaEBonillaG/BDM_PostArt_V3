<?php
<<<<<<< Updated upstream
$servidor = "localhost";
=======
$servidor = "localhost:3306";
>>>>>>> Stashed changes
$usuario = "root";
$contrasena = "Admin";
$basedatos = "PostArt";


$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
<<<<<<< Updated upstream
echo " ";
=======
echo "Conexión exitosa";
>>>>>>> Stashed changes
?>