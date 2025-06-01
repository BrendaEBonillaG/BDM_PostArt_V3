<?php
require './Conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: Index.php");
  exit();
}

$id_usuario = $_SESSION['usuario']['ID_Usuario'];

// 1. Chats privados
$stmtPrivado = $conexion->prepare("CALL ObtenerUsuariosYChatsPrivados(?)");
$stmtPrivado->bind_param("i", $id_usuario);
$stmtPrivado->execute();
$resultPrivado = $stmtPrivado->get_result();

// 🔽 Añade esto para evitar el error
while ($conexion->more_results() && $conexion->next_result()) {
    $extraResult = $conexion->use_result();
    if ($extraResult instanceof mysqli_result) {
        $extraResult->free();
    }
}


// 2. Chats grupales
$stmtGrupal = $conexion->prepare("CALL ObtenerChatsGrupalesDeUsuario(?)");
$stmtGrupal->bind_param("i", $id_usuario);
$stmtGrupal->execute();
$resultGrupal = $stmtGrupal->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PostArt | Chat</title>
  <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/style.css">
  <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/Carrito.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="usuarios-container">
  <div class="user-list">
    <h3>Selecciona un chat para comenzar</h3>
    <ul>

      <?php if ($resultPrivado->num_rows > 0): ?>
        <?php foreach ($resultPrivado as $row):
          $nombre_usuario = htmlspecialchars($row['nombreUsu']);
          $imagen_perfil = !empty($row['foto'])
            ? 'data:image/jpeg;base64,' . base64_encode($row['foto'])
            : 'https://i.pinimg.com/736x/dc/6c/b0/dc6cb0521d182f959da46aaee82e742f.jpg';
          $id_chat = $row['id_chat'];
        ?>
          <li>
            <img src="<?= $imagen_perfil ?>" alt="Foto" class="profile-pic">
            <a href="#" class="usuario-chat" data-id-chat="<?= $id_chat ?>" data-nombre="<?= $nombre_usuario ?>"
               data-foto="<?= $imagen_perfil ?>" data-tipo="privado">
              <?= $nombre_usuario ?>
            </a>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if ($resultGrupal->num_rows > 0): ?>
        <?php foreach ($resultGrupal as $grupo):
          $nombre_grupo = htmlspecialchars($grupo['nombre']);
          $imagen_grupo = !empty($grupo['imagen'])
            ? 'data:image/jpeg;base64,' . base64_encode($grupo['imagen'])
            : 'https://cdn-icons-png.flaticon.com/512/74/74472.png'; // imagen default para grupos
          $id_chat_grupal = $grupo['id_chat'];
        ?>
          <li>
            <img src="<?= $imagen_grupo ?>" alt="Grupo" class="profile-pic">
            <a href="#" class="usuario-chat" data-id-chat="<?= $id_chat_grupal ?>" data-nombre="<?= $nombre_grupo ?>"
               data-foto="<?= $imagen_grupo ?>" data-tipo="grupal">
              <?= $nombre_grupo ?> (grupo)
            </a>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if ($resultPrivado->num_rows == 0 && $resultGrupal->num_rows == 0): ?>
        <li>No tienes chats activos</li>
      <?php endif; ?>

    </ul>
  </div>

  <div class="chat-container">
    <div class="chat-box" id="chat-box">
      <!-- Aquí se mostrarán los mensajes dinámicamente -->
    </div>
    <div class="chat-footer">
      <input type="text" id="message-input" placeholder="Escribe un mensaje...">
      <button id="clip-btn" class="clip-btn"><i class="fas fa-paperclip"></i></button>
      <button id="send-btn" class="send-btn">Enviar</button>
    </div>
  </div>

  <div class="profile-card" id="profile-card">
    <img src="https://i.pinimg.com/736x/dc/6c/b0/dc6cb0521d182f959da46aaee82e742f.jpg" alt="Foto de perfil"
         class="profile-pic2" id="foto-usuario">
    <div class="profile-info">
      <h3 id="nombre-usuario">Usuario</h3>
      <p>Hola 👋</p>
    </div>
  </div>
</div>

<div class="button-container">
  <button onclick="location.href='index.php'">
    <img src="../BDM_PostArt_V3/imagenes-prueba/home.png" alt="Home">
  </button>
  <button>
    <img src="../BDM_PostArt_V3/imagenes-prueba/mensaje-recibido.png" alt="Notificación">
  </button>
</div>

<script src="./JS/ChatFunc.js" defer></script>
<script src="./JS/cambiar-chat.js" defer></script>

</body>
</html>
