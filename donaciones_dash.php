<?php
session_start();

require __DIR__ . '../Conexion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ../Login.html');
    exit();
}

$usuario = $_SESSION['usuario'];

// Convertir la imagen de base64 a src si existe, si no usar imagen por defecto
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
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/donaciones_dash.css">
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
    <!-- posts -->
    <div class="container-picture-dashboard">


        <!-- post proyectos -->
<div class="container-picture-dashboard">
    <?php include("php/cargar_proyectos.php"); ?>
</div>

        
      
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="../BDM_PostArt_V3/js/script.js"></script>
    <script src="../BDM_PostArt_V3/js/enlaces.js"></script>
</body>

</html>