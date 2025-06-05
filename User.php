<?php
session_start();

$user = []; // Inicializamos como array vacío

if (isset($_SESSION['usuario'])) {
    $user_id = $_SESSION['usuario']['ID_Usuario']; // Corregido para tomar solo el ID

    require_once __DIR__ . '../Conexion.php'; // Corrección en la ruta

    $stmt = $conexion->prepare("CALL GetUserProfileInfo(?)");
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conexion->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($id, $nombre, $apePa, $apeMa, $nick, $correo, $biografia, $foto_perfil, $rol);

    if ($stmt->fetch()) {
        $user = [
            'ID_Usuario' => $id,
            'Nombre' => $nombre,
            'ApePa' => $apePa,
            'ApeMa' => $apeMa,
            'Nickname' => $nick,
            'Correo' => $correo,
            'Biografia' => $biografia,
            'Foto_perfil' => $foto_perfil,
            'Rol' => $rol
        ];
    } else {
        echo "No se encontraron datos para este usuario.";
    }

    $stmt->close();
    $conexion->close();
} else {
    header('Location: Login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../BDM_POSTART_V3/CSS/stylex.css">
    <link rel="stylesheet" href="../BDM_POSTART_V3/CSS/header.css">
    <link rel="stylesheet" href="../BDM_POSTART_V3/CSS/navegador.css">
    <link rel="stylesheet" href="../BDM_POSTART_V3/CSS/cartas.css">
    <title>PostArt | User</title>
</head>

<body>
    <header>
        <div class="base-header">
            <!-- barra de logo -->
            <div class="base-header-logo">
                <img src="../BDM_POSTART_V3/imagenes-prueba/logo1.png" alt="">
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
            <div class="avatar-image">
                <?php
                $fotoPerfilSrc = !empty($user['Foto_perfil'])
                    ? 'data:image/jpeg;base64,' . base64_encode($user['Foto_perfil'])
                    : '../BDM_POSTART_V3/imagenes-prueba/User.jpg';
                ?>
                <img src="<?= $fotoPerfilSrc ?>" alt="Avatar">
            </div>

        </div>
        <div class="perfile-avatar-status"></div>
    </div>

    <!-- Menú del perfil -->
    <div class="menu-avatar oculto" id="menuAvatarjs">
        <div class="avatar-menu">
            <div class="avatar-menu">
                <?php
                $fotoPerfilSrc = !empty($user['Foto_perfil'])
                    ? 'data:image/jpeg;base64,' . base64_encode($user['Foto_perfil'])
                    : '../BDM_POSTART_V3/imagenes-prueba/User.jpg';
                ?>
                <img src="<?= $fotoPerfilSrc ?>" alt="Avatar">
            </div>

        </div>
        <div class="content-menu-perfil">
            <div class="menu-perfil-nametag">
                <h3><?php echo htmlspecialchars($user['Nickname']); ?></h3>
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
    <!-- menu add -->
    <div class="menu-add-perfil">
        <button onclick="location.href='index.php'" class="icon-button">
            <i class='bx bxs-home'></i>
        </button>
        <button onclick="location.href='galery.php'" class="icon-button">
            <i class='bx bxs-photo-album'></i>
        </button>
    </div>

    <!-- Pefil info -->
    <div class="container-perfil-info-dashboard">
        <div class="container-perfil-info">
            <div class="avatar-perfil">
                <?php if (!empty($user['Foto_perfil'])): ?>
                    <?php
                    $imagenBlob = $user['Foto_perfil'];
                    $imagenBase64 = base64_encode($imagenBlob);
                    ?>
                    <img src="data:image/jpeg;base64,<?= $imagenBase64 ?>" alt="Foto de perfil" class="profile-img">
                <?php else: ?>
                    <img src="../BDM_POSTART_V3/imagenes-prueba/User.jpg" alt="Foto por defecto" class="profile-img">
                <?php endif; ?>
            </div>

            <div class="content-perfil-info" id="perfil-info-toggle">
                <div class="details-perfil-info">
                    <h2>
                        <?= !empty($user['Nickname']) ? htmlspecialchars($user['Nickname']) : 'Usuario'; ?><br>
                        <span><?= !empty($user['Rol']) ? htmlspecialchars($user['Rol']) : 'Invitado'; ?></span>
                    </h2>
                    <h4><?= !empty($user['Biografia']) ? htmlspecialchars($user['Biografia']) : 'Sin biografía'; ?></h4>
                    <br>
                    <div class="data-perfil-info">
                        <h3>50<br><span>Post</span></h3>
                        <h3>62<br><span>Followers</span></h3>
                        <h3>5<br><span>Following</span></h3>
                    </div>

                    <div class="actionBtn-User-info">
                        <button id="editButton">Edit info</button>
                    </div>
                </div>
            </div>



            <div class="perfil-info-social" id="perfil-info-social">
                <div class="perfil-info-social-control">
                    <!--Toggle Button-->
                    <div class="perfil-info-social-toggle">
                        <i class='bx bx-cross'></i>

                    </div>
                    <span class="perfil-info-social-text"><?php echo $user['Correo']; ?></span>

                    <!--Card Social-->
                    <ul class="perfil-info-social-list">

                        <a href="https://www.facebook.com/" class="perfil-info-social-link">
                            <i class='bx bxl-linkedin'></i>
                        </a>
                        <a href="https://www.instagram.com/" class="perfil-info-social-link">
                            <i class='bx bxl-instagram'></i>
                        </a>
                        <a href="https://twitter.com/" class="perfil-info-social-link">
                            <i class='bx bxl-twitter'></i>
                        </a>
                        <a href="https://youtube.com/" class="perfil-info-social-link">
                            <i class='bx bxl-youtube'></i>
                        </a>

                    </ul>

                </div>
            </div>

        </div>

    </div>



    <!-- Modal editar perfil -->
    <div id="editUser" class="modal-edit-user">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Información</h2>
            <form id="editForm" method="POST" action="ActualizarUsu.php" enctype="multipart/form-data">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['Nombre']); ?>"
                    required>

                <label for="surname1">Apellido paterno:</label>
                <input type="text" id="surname1" name="surname1" value="<?php echo htmlspecialchars($user['ApePa']); ?>"
                    required>

                <label for="surname2">Apellido materno:</label>
                <input type="text" id="surname2" name="surname2" value="<?php echo htmlspecialchars($user['ApeMa']); ?>"
                    required>

                <label for="email">Correo:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Correo']); ?>"
                    required>

                <div class="input-box">
                    <input type="file" class="input-field" name="foto" accept="image/*">
                    <i class="bx bx-image icon"></i>
                </div>

                <label for="description">Descripción:</label>
                <textarea id="description" name="description"
                    required><?php echo htmlspecialchars($user['Biografia']); ?></textarea>

                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="../BDM_PostArt_V3/js/script.js"></script>
    <script src="../BDM_PostArt_V3/js/enlaces.js"></script>
</body>

</html>