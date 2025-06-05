<?php
include("../Conexion.php");

$catQuery = $conexion->query("SELECT Id_Categoria, Nombre FROM Categorias");

while ($cat = $catQuery->fetch_assoc()) {
    echo "<option value='{$cat['Id_Categoria']}'>{$cat['Nombre']}</option>";
}
?>
