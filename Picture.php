<?php
session_start();
require __DIR__ . '../Conexion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../Login.html');
    exit();
}
// echo "<pre>"; print_r($_SESSION['usuario']); echo "</pre>"; exit();
$usuario = $_SESSION['usuario'];

// Variables del NAVBAR (dejan igual)
$fotoPerfilSrc = $usuario['Foto_perfil']
    ? 'data:image/jpeg;base64,' . $usuario['Foto_perfil']
    : 'imagenes-prueba/User.jpg';

$nickname = $usuario['Nickname'];
$rol = $usuario['Rol'];
$biografia = $usuario['Biografia'] ?? 'Artista sin descripción';

// Guardar nuevo comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $comentarioTexto = trim($_POST['comentario']);
    $idUsuario = $usuario['ID_Usuario']; // asumimos que la sesión contiene este valor
    $idPublicacion = intval($_GET['id']);

    if (!empty($comentarioTexto)) {
        $stmtComentario = $conexion->prepare("INSERT INTO Comentarios (Id_publicacion, Id_usuario, Comentario) VALUES (?, ?, ?)");
        $stmtComentario->bind_param("iis", $idPublicacion, $idUsuario, $comentarioTexto);
        $stmtComentario->execute();
        $stmtComentario->close();

        // Redirigir para evitar reenvío del formulario
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

// Validar ID del post
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de publicación no válido.");
}

$idPublicacion = intval($_GET['id']);
$id_post = $idPublicacion;


// Obtener datos del post y su autor
$stmt = $conexion->prepare("CALL SP_ObtenerPublicacionPorID(?)");
$stmt->bind_param("i", $idPublicacion);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Publicación no encontrada.");
}

$publicacion = $res->fetch_assoc();
$stmt->close();

// Obtener comentarios de la publicación
$comentarios = [];
$stmtComentarios = $conexion->prepare("
    SELECT c.Comentario, c.Fecha_comentario, u.Nickname 
    FROM Comentarios c 
    JOIN Usuario u ON c.Id_usuario = u.Id_usuario 
    WHERE c.Id_publicacion = ? AND c.Estado = 'Activo' 
    ORDER BY c.Fecha_comentario DESC
");
$stmtComentarios->bind_param("i", $idPublicacion);
$stmtComentarios->execute();
$resComentarios = $stmtComentarios->get_result();

while ($coment = $resComentarios->fetch_assoc()) {
    $comentarios[] = $coment;
}
$stmtComentarios->close();


// Variables del POST
$tituloPublicacion = $publicacion['Titulo'];
$imagenPublicacion = $publicacion['Imagen'];
$imagenSrc = 'data:image/jpeg;base64,' . base64_encode($imagenPublicacion);

// Variables del AUTOR del POST (nuevas variables)
$autorNickname = $publicacion['Nickname'];
$autorRol = $publicacion['Rol'];
$autorFotoPerfil = $publicacion['Foto_perfil']
    ? 'data:image/jpeg;base64,' . base64_encode($publicacion['Foto_perfil'])
    : 'imagenes-prueba/User.jpg';
$id_creador = $publicacion['ID_Usuario']; // asegúrate de que el SP también devuelve esto


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/stylex.css">
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/header.css">
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/navegador.css">
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/cartas.css">
    <title>PostArt | publicar</title>
</head>

<body>
    <header>
        <div class="base-header">
            <!-- barra de logo -->
            <div class="base-header-logo">
                <img src="../BDM_PostArt_V3/imagenes-prueba/logo1.png" alt="">
            </div>
            <!-- barra de busqueda -->
            <div class="container-base-header-search-bar">
                <input type="text" class="search-bar-dashboard" placeholder="Search...">
            </div>
            <!-- barra de notificaciones -->
            <div class="activity-header-bar">

                <div class="message-botton-activity-bar">
                    <button onclick="location.href='groups_dash.html'">
                        <i class='bx bxs-message-error'></i>
                    </button>
                    <button onclick="location.href='Chat.php'" class="icon-button">
                        <i class='bx bxs-message-minus'></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    <!-- Botón del menú -->
    <div class="avatar-boton-card" id="botonAvatarMenujs">
        <div class="avatar-image">
            <img src="<?php echo $fotoPerfilSrc; ?>" alt="Avatar">
        </div>
        <div class="perfile-avatar-status"></div>
    </div>

    <!-- Menú del perfil -->
    <div class="menu-avatar oculto" id="menuAvatarjs">
        <div class="avatar-menu">
            <img src="<?php echo $fotoPerfilSrc; ?>" alt="Avatar">
        </div>
        <div class="content-menu-perfil">
            <div class="menu-perfil-nametag">
                <h3><?php echo htmlspecialchars($nickname); ?></h3>
                <h5><?php echo htmlspecialchars($rol); ?></h5>
                <h6><?php echo htmlspecialchars($biografia); ?></h6>
            </div>
            <div class="menu-tapa"></div>
            <div class="menu-perfil-btn">
                <div class="menu-perfil-btn-base1">
                    <div class="menu-perfil-btn-base2">
                        <i class='bx bx-menu'></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="btns-menu-profile">
            <span><i class='bx bxs-user'></i></span>
            <span><i class='bx bxs-hot menu-favoritos'></i></span>
            <span><i class='bx bxs-add-to-queue'></i></span>
            <span><i class='bx bxs-donate-heart'></i></span>
            <span ><i class='bx bx-plus-circle'></i></span>
            <span><i class='bx bx-log-out'></i></span>
        </div>
    </div>

    <div class="pantalla-blur oculto" id="pantallaBlurjs"></div>
    <!-- modal log out-->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <h2 class="modal-title">Log out confirmation</h2>
            <p class="modal-message">Are you sure you want to log out?</p>
            <div class="modal-buttons">
                <button id="yesBtn">Yes</button>
                <button id="noBtn">No</button>
            </div>
        </div>
    </div>
    <div class="space-container-area">
        <<div class="left-space-zone">
            <div class="contenedor-card-perfil">
    <div class="avatar-perfil-publicar">
        <img src="<?php echo $autorFotoPerfil; ?>" alt="Avatar del autor">
    </div>
    <div class="content-perfil-publicar-info-user">
        <h2><?php echo htmlspecialchars($autorNickname); ?></h2>
        <h4><?php echo htmlspecialchars($autorRol); ?></h4>
    </div>
</div>
<?php echo "ID del creador: " . $id_creador; ?>
            <div class="add-homeBtn">
                <!-- Botón de inicio  -->
                <button onclick="location.href='index.php'" class="icon-button" title="Inicio">
                    <i class='bx bxs-home'></i>
                </button>

                <!-- Botón de perfil del creador -->
                <button onclick="location.href='Perfil.php?id=<?php echo $id_creador; ?>&t=<?php echo time(); ?>'" class="icon-button" title="Perfil del creador">
                    <i class='bx bxs-user'></i>
                </button>
                

                <!-- Botón de seguir -->
                <button onclick="seguirUsuario(<?php echo $id_creador; ?>)" class="icon-button" title="Seguir">
                    <i class='bx bxs-user-plus'></i>
                </button>

                <!-- Botón de like -->
                <button onclick="darLike(<?php echo $id_post; ?>)" class="icon-button" title="Me gusta">
                    <i class='bx bxs-like'></i>
                </button>
            </div>

    </div>

    <div class="center-space-zone">
        <div class="upload-space-zone">
            <img src="<?php echo $imagenSrc; ?>" alt="<?php echo htmlspecialchars($tituloPublicacion); ?>">
        </div>

    </div>


<div class="right-space-zone">
    <div class="upload-space-zone-info">
        <div class="upload-space-zone-title-form-container">
            <h2 style="color: blue; font-size: 24px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($tituloPublicacion); ?>
            </h2>
        </div>

        <!-- Mostrar los comentarios primero -->
<?php if (!empty($comentarios)): ?>
    <div class="comentarios" style="margin-bottom: 20px;">
        <h3>Comentarios:</h3>
        
        <!-- Contenedor con scroll si hay más de 3 comentarios -->
        <div style="
            max-height: 450px; /* Altura aproximada para 3 comentarios */
            overflow-y: auto;
            padding-right: 10px; /* Para evitar cortar contenido por el scroll */
            border: 1px solid #ddd;
            border-radius: 10px;
        ">
            <?php foreach ($comentarios as $comentario): ?>
                <div class="comentario" style="margin-bottom: 10px; padding: 10px; border-bottom: 1px solid #ccc;">
                    <div style="
                        display: flex;
                        flex-direction: column;
                        padding: 15px;
                        margin-bottom: 15px;
                        border: 1px solid #ccc;
                        border-radius: 10px;
                        background-color: #f9f9f9;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                    ">
                        <p style="font-size: 16px; margin: 0 0 8px; color: #333;">
                            <?php echo nl2br(htmlspecialchars($comentario['Comentario'])); ?>
                        </p>
                        <div style="font-size: 13px; color: #777;">
                            <span>Por <strong><?php echo htmlspecialchars($comentario['Nickname']); ?></strong></span>
                            <span> • <?php echo date('d/m/Y H:i', strtotime($comentario['Fecha_comentario'])); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>


        <!-- Formulario de nuevo comentario -->
        <form method="post" action="">
            <textarea name="comentario" rows="4" cols="50" placeholder="Escribe tu comentario aquí..." required></textarea>
            <input type="hidden" name="publicacion_id" value="<?php echo htmlspecialchars($idPublicacion); ?>">
            <br>
            <button type="submit">Comentar</button>
        </form>
    </div>
</div>


    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="../BDM_PostArt_V3/js/script.js"></script>
    <script src="../BDM_PostArt_V3/js/enlaces.js"></script>
</body>

</html>