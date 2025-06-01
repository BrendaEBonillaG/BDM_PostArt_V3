<?php
require './Conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: Index.php");
  exit();
}

$id_usuario = $_SESSION['usuario']['ID_Usuario'];

// Usamos el procedure directamente
$stmt = $conexion->prepare("CALL ObtenerUsuariosYChatsPrivados(?)");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PostArt | Chat Privado</title>
  <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/style.css">
  <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/Carrito.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="usuarios-container">
  <div class="user-list">
    <h3>Select one user to start chat</h3>
    <ul>
      <?php if ($result->num_rows > 0): ?>
        <?php foreach ($result as $row):
          $nombre_usuario = htmlspecialchars($row['nombreUsu']);
          $imagen_perfil = !empty($row['foto'])
            ? 'data:image/jpeg;base64,' . base64_encode($row['foto'])
            : 'https://i.pinimg.com/736x/dc/6c/b0/dc6cb0521d182f959da46aaee82e742f.jpg';
          $id_chat = $row['id_chat'];
        ?>
          <li>
            <img src="<?= $imagen_perfil ?>" alt="Foto" class="profile-pic">
            <a href="#" class="usuario-chat" 
               data-id-chat="<?= $id_chat ?>" 
               data-nombre="<?= $nombre_usuario ?>"
               data-foto="<?= $imagen_perfil ?>">
              <?= $nombre_usuario ?>
            </a>
          </li>
        <?php endforeach; ?>
      <?php else: ?>
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
