<?php
session_start();

require __DIR__ . '../Conexion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../Login.html');
    exit();
}

$usuario = $_SESSION['usuario'];
$id_usuario = $usuario['ID_Usuario'];  // Necesitamos el ID del usuario logueado

$fotoPerfilSrc = $usuario['Foto_perfil']
    ? 'data:image/jpeg;base64,' . $usuario['Foto_perfil']
    : 'imagenes-prueba/User.jpg';

$nickname = $usuario['Nickname'];
$rol = $usuario['Rol'];
$biografia = $usuario['Biografia'] ?? 'Artista sin descripción';
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
    <title>PostArt | Home</title>
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
            <span><i class='bx bx-plus-circle'></i></span>
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


    <div class="container-picture-dashboard">
        <?php
      
        $likes_usuario = [];
        $stmtLikes = $conexion->prepare("SELECT Id_publicacion FROM Me_Gusta WHERE Id_usuario = ?");
        $stmtLikes->bind_param("i", $id_usuario);
        $stmtLikes->execute();
        $resultLikes = $stmtLikes->get_result();
        while ($likeRow = $resultLikes->fetch_assoc()) {
            $likes_usuario[] = $likeRow['Id_publicacion'];
        }
        $stmtLikes->close();

        // AHORA sí podemos llamar al SP sin problema
        $stmt = $conexion->prepare("CALL SP_ObtenerPublicacionesActivas()");
        $stmt->execute();
        $resultado = $stmt->get_result();


        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $imagenCodificada = base64_encode($fila['Imagen']);
                $src = !empty($fila['Imagen']) ? 'data:image/jpeg;base64,' . $imagenCodificada : "imagenes-prueba/default_post.jpg";
                $perfilSrc = !empty($fila['Foto_perfil']) ? 'data:image/jpeg;base64,' . base64_encode($fila['Foto_perfil']) : "imagenes-prueba/User.jpg";
                $nombre = htmlspecialchars($fila['Nombre']);
                $rol = htmlspecialchars($fila['Rol']);
                $titulo = htmlspecialchars($fila['Titulo']);
                $idPublicacion = intval($fila['Id_publicacion']);

                // ✅ Verificamos si este post tiene like por el usuario
                $yaTieneLike = in_array($idPublicacion, $likes_usuario);
                $claseLike = $yaTieneLike ? "active-like" : "";

                echo "
        <div class='card-image-post'>
            <a href='Picture.php?id={$idPublicacion}' class='tag-artist-info'>
                <div class='tag-artist-avatar'>
                    <img src='{$perfilSrc}' alt='Perfil de Usuario'>
                </div>
                <div class='tag-artist-name'>
                    <h3>{$nombre}</h3><h6>{$rol}</h6>
                </div>
            </a>
            <div class='tag-paw-botton paw-button {$claseLike}' data-publicacion-id='{$idPublicacion}'>
                <i class='bx bxs-hot'></i>
            </div>
            <a href='Picture.php?id={$idPublicacion}' class='imag' id='cardImagePost'>
                <img src='{$src}' alt='{$titulo}'>
            </a>
        </div>";
            }
        } else {
            echo "<p>No hay publicaciones disponibles.</p>";
        }

        $stmt->close();
        ?>
    </div>

    <script>
        document.querySelectorAll('.paw-button').forEach(boton => {
            boton.addEventListener('click', () => {
                const idPublicacion = boton.dataset.publicacionId;

                fetch('PHP/RegistrarLike.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id_publicacion=' + encodeURIComponent(idPublicacion)
                })
                    .then(response => response.text())
                    .then(result => {
                        alert(result);
                        boton.classList.toggle('active-like');  // ✅ Alternamos visualmente el estado
                    })
                    .catch(err => {
                        console.error("Error al registrar like: ", err);
                    });
            });
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="../BDM_PostArt_V3/js/script.js"></script>
    <script src="../BDM_PostArt_V3/js/enlaces.js"></script>
</body>

</html>