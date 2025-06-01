<?php
include '../Conexion.php';
session_start();

if (!isset($_GET['id_grupo']) || !isset($_SESSION['id_usuario'])) {
  exit('ID de grupo o sesión no válida');
}

$id_grupo = intval($_GET['id_grupo']);
$id_usuario_actual = $_SESSION['id_usuario'];
$clave_secreta = "clave_secreta_123"; // Debería ser la misma que en ChatFunc.js

// Consulta modificada para incluir el campo 'encriptado'
$stmt = $conn->prepare("
  SELECT m.contenido, m.fecha_envio, m.id_usuario, u.nombre_usuario, m.encriptado, m.tipo
  FROM Mensajes_Grupales m
  INNER JOIN Usuarios u ON m.id_usuario = u.id_usuario
  WHERE m.id_chat_Grupal = ?
  ORDER BY m.fecha_envio ASC
");

$stmt->bind_param("i", $id_grupo);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  $esMio = $row['id_usuario'] == $id_usuario_actual;
  $clase = $esMio ? 'mensaje-propio' : 'mensaje-otro';
  $contenido = $row['contenido'];

  // Desencriptar si el mensaje está encriptado
  if ($row['encriptado']) {
  $tipo = isset($row['tipo']) ? $row['tipo'] : null;

  if ($tipo !== 'archivo') {
    $contenido = openssl_decrypt(
      $contenido,
      'AES-256-CBC',
      $clave_secreta,
      0,
      substr(md5($clave_secreta), 0, 16)
    );

    if ($contenido === false) {
      $contenido = "[Mensaje encriptado - error al desencriptar]";
      error_log("Error desencriptando mensaje del usuario " . $row['id_usuario']);
    }
  }
}


  echo '<div class="mensaje ' . $clase . '">';
  if (!$esMio) {
    echo '<strong>' . htmlspecialchars($row['nombre_usuario']) . ':</strong><br>';
  }
  echo '<span>' . htmlspecialchars($contenido) . '</span>';

  // Mostrar ícono de candado si está encriptado
  if ($row['encriptado']) {
    echo ' <i class="bi bi-lock-fill" style="font-size: 0.8rem;"></i>';
  }

  echo '<br><small>' . $row['fecha_envio'] . '</small>';
  echo '</div>';
}

$stmt->close();
$conn->close();
?>
