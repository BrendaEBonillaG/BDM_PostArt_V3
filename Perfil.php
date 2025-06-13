<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();
require __DIR__ . '/Conexion.php';

// Validación de sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../Login.html');
    exit();
}

// Validar ID del artista por GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}
$idArtista = intval($_GET['id']);

// Obtener datos del usuario logueado
$idUsuarioLog = $_SESSION['usuario']['ID_Usuario'];
$stmtUsuario = $conexion->prepare("CALL ObtenerDatosUsuario(?)");
$stmtUsuario->bind_param("i", $idUsuarioLog);
$stmtUsuario->execute();
$resUsuario = $stmtUsuario->get_result();

if ($resUsuario->num_rows > 0) {
    $usuarioLog = $resUsuario->fetch_assoc();
}
$stmtUsuario->close();
while ($conexion->more_results() && $conexion->next_result()) { $conexion->use_result(); }

// Obtener total de seguidores del artista
$stmtSeguidores = $conexion->prepare("CALL ObtenerTotalSeguidores(?)");
$stmtSeguidores->bind_param("i", $idArtista);
$stmtSeguidores->execute();
$resSeguidores = $stmtSeguidores->get_result();

$totalSeguidores = 0;
if ($resSeguidores && $resSeguidores->num_rows > 0) {
    $fila = $resSeguidores->fetch_assoc();
    $totalSeguidores = $fila['total_seguidores'];
}
$resSeguidores->close();
$stmtSeguidores->close();
while ($conexion->more_results() && $conexion->next_result()) { $conexion->use_result(); }


// Obtener datos del artista
$stmt = $conexion->prepare("CALL ObtenerDatosPerfilArtista(?)");
$stmt->bind_param("i", $idArtista);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<h2>Artista no encontrado</h2>";
    exit();
}

$artista = $res->fetch_assoc();
$stmt->close();
while ($conexion->more_results() && $conexion->next_result()) { $conexion->use_result(); }

// Datos del artista
$fotoPerfilArtista = !empty($artista['Foto_perfil']) ? 'data:image/jpeg;base64,' . base64_encode($artista['Foto_perfil']) : 'imagenes-prueba/User.jpg';
$nicknameArtista = htmlspecialchars($artista['Nickname']);
$rolArtista = htmlspecialchars($artista['Rol']);
$biografiaArtista = !empty($artista['Biografia']) ? htmlspecialchars($artista['Biografia']) : 'Artista sin descripción';
$correoArtista = htmlspecialchars($artista['Correo']);
$facebook = $artista['Facebook'] ?? '#';
$instagram = $artista['Instagram'] ?? '#';
$twitter = $artista['Twitter'] ?? '#';
$youtube = $artista['Youtube'] ?? '#';

// Datos del usuario logueado
$fotoUsuario = !empty($usuarioLog['Foto_perfil']) && is_string($usuarioLog['Foto_perfil'])
    ? 'data:image/jpeg;base64,' . base64_encode($usuarioLog['Foto_perfil'])
    : 'imagenes-prueba/User.jpg';

$nicknameUsuario = htmlspecialchars($usuarioLog['Nickname']);
$rolUsuario = htmlspecialchars($usuarioLog['Rol']);
$biografiaUsuario = !empty($usuarioLog['Biografia']) ? htmlspecialchars($usuarioLog['Biografia']) : 'Sin biografía';
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>PostArt | Perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../BDM_POSTART_V3/CSS/stylex.css">
    <link rel="stylesheet" href="../BDM_POSTART_V3/CSS/header.css">
    <link rel="stylesheet" href="../BDM_POSTART_V3/CSS/navegador.css">
    <link rel="stylesheet" href="../BDM_POSTART_V3/CSS/cartas.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <header>
        <div class="base-header">
            <div class="base-header-logo">
                <img src="../BDM_PostArt_V3/imagenes-prueba/logo1.png" alt="">
            </div>
            <div class="container-base-header-search-bar">
                <input type="text" class="search-bar-dashboard" placeholder="Search...">
            </div>
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

    <!-- Menú del usuario logueado -->
    <div class="avatar-boton-card" id="botonAvatarMenujs">
        <div class="avatar-image">
            <img src="<?php echo $fotoUsuario; ?>" alt="Avatar">
        </div>
        <div class="perfile-avatar-status"></div>
    </div>

    <div class="menu-avatar oculto" id="menuAvatarjs">
        <div class="avatar-menu">
            <img src="<?php echo $fotoUsuario; ?>" alt="Avatar">
        </div>
        <div class="content-menu-perfil">
            <div class="menu-perfil-nametag">
                <h3><?php echo $nicknameUsuario; ?></h3>
                <h5><?php echo $rolUsuario; ?></h5>
                <h6><?php echo $biografiaUsuario; ?></h6>
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

    <div class="menu-add-perfil">
        <button onclick="location.href='index.php'" class="icon-button">
            <i class='bx bxs-home'></i>
        </button>
        <button onclick="location.href='galery.php'" class="icon-button">
            <i class='bx bxs-photo-album'></i>
        </button>
    </div>

    <!-- Contenido del perfil del artista -->
    <div class="container-perfil-info-dashboard">
        <div class="container-perfil-info">
            <div class="avatar-perfil">
                <img src="<?php echo $fotoPerfilArtista; ?>" alt="">
            </div>
            <div class="content-perfil-info" id="perfil-info-toggle">
                <div class="details-perfil-info">
                    <h2><?php echo $nicknameArtista; ?><br><span><?php echo $rolArtista; ?></span></h2>
                    <h4><?php echo $biografiaArtista; ?></h4>
                    <br>
                    <div class="data-perfil-info">
                        <h3>--<br><span>Post</span></h3>
                      <h3><?php echo $totalSeguidores; ?><br><span>Followers</span></h3>

                        <h3>--<br><span>Following</span></h3>
                    </div>
                    <div class="actionBtn-perfil-info" style="gap: 8px;">
                        <button id="btnFollow" data-artista="<?php echo $idArtista; ?>">Follow</button>

                        <button>Subs</button>
                        <button onclick="location.href='Chat.php'">Message</button>
                    </div>
                </div>
            </div>
            <div class="perfil-info-social" id="perfil-info-social">
                <div class="perfil-info-social-control">
                    <div class="perfil-info-social-toggle">
                        <i class='bx bx-cross'></i>
                    </div>
                    <span class="perfil-info-social-text"><?php echo $correoArtista; ?></span>
                    <ul class="perfil-info-social-list">
                        <a href="<?php echo $facebook; ?>" class="perfil-info-social-link" target="_blank"><i
                                class='bx bxl-linkedin'></i></a>
                        <a href="<?php echo $instagram; ?>" class="perfil-info-social-link" target="_blank"><i
                                class='bx bxl-instagram'></i></a>
                        <a href="<?php echo $twitter; ?>" class="perfil-info-social-link" target="_blank"><i
                                class='bx bxl-twitter'></i></a>
                        <a href="<?php echo $youtube; ?>" class="perfil-info-social-link" target="_blank"><i
                                class='bx bxl-youtube'></i></a>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../BDM_PostArt_V3/js/script.js"></script>
<script src="../BDM_PostArt_V3/js/enlaces.js"></script>

<script>
    $('#btnFollow').on('click', function () {
        const idArtista = $(this).data('artista');

        $.ajax({
            url: 'PHP/follow.php',
            method: 'POST',
            data: {
                artista_id: idArtista
            },
            success: function (response) {
                if (response === 'ok') {
                    alert('Ahora sigues a este artista.');
                    $('#btnFollow').text('Following').prop('disabled', true);
                } else {
                    alert('Error al seguir al artista.');
                }
            },
            error: function () {
                alert('Error de conexión con el servidor.');
            }
        });
    });
</script>

</body>

</html>