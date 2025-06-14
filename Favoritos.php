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
    <title>PostArt | Favoritos</title>
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
        <div class="left-space-zone">
            <div class="contenedor-card-perfil">
                <div class="avatar-perfil-publicar">
                    <img src="<?php echo $fotoPerfilSrc; ?>" alt="Avatar">
                </div>
                <div class="content-perfil-publicar-info-user">
                    <h2><?php echo htmlspecialchars($nickname); ?></h2>
                    <h4><?php echo htmlspecialchars($rol); ?></h4>
                </div>
            </div>
            <div class="add-homeBtn">
                <button onclick="location.href='index.php'" class="icon-button">
                    <i class='bx bxs-home'></i>
                </button>
            </div>
        </div>


        <!-- posts -->
        <div class="container-picture-dashboard">

            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/0fb2c7aa2e294b64d7a2eceb03334803.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/21a0ac2bcecfc466962306b1640f9517.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/4e8df9686b0e6156483b9ff5ed55bdcb.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/5c5ccb74f485e6ba404f434a2e6d849b.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/6188a23828f571155793b5094dff7c1c.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/620311d1dce41494a280910664afc66e.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/717bf1c2ae9f34f87aa4560b9b7edacf.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/7a14a1e3805acff8570887ab1750e7b8.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/84ae2c04a42c932fccc089cb686e834a.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/8a471dd20c5d36db726f76f203577c0c.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/8f0f0b588b89cf5040d9e5e569129faa.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/a7bb8eba8d78e98b2e5384cfedc0c428.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/b034b3e1142e9c7224e0ef7619f87ad8.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/b38a890e4729eaee66968e990925607c.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/bcef107a474d14c0fa1adf79ebb1ffdd.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/d8de28f6f144b5ace16a295454a8f73d.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/de2af81df15893035f7b11260bb4fb58.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/de8df33b62e3fb4637d7b644a0438ac1.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/ff0560f6673617af93564a32ee6cd8d2.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/prueba.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/zzzzzzzzzzzzzjajzajz.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/0a53aa26f3577d2020203244247e0093.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/GbS0n4cWEAAkFgj.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/GUHjGVuWoAAXXJG.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/GVvAQF9WAAA29Hn.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/GWbFFmvXsAEwVS1.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/GXEem7IWwAAtt3M.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/thumb-1920-1289490.png">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/thumb-1920-1294309.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/508c939359f111936014e714184b89d3.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/victoria-lyons-frogtownrender-00.jpg">
                </div>
            </div>
            <!-- post- imagen -->
            <div class="card-image-post">
                <div class="tag-artist-info">
                    <div class="tag-artist-avatar">
                        <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
                    </div>
                    <div class="tag-artist-name">
                        <h3>Blackat</h3>
                        <h6>2D artist</h6>
                    </div>
                </div>
                <div class="tag-paw-botton paw-button">
                    <i class='bx bxl-baidu'></i>
                </div>
                <div class="imag" id="cardImagePost">
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/2f0bb0f234c9dabd561a55dc1a36a944.jpg">
                </div>
            </div>

        </div>



    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="../BDM_PostArt_V3/js/script.js"></script>
    <script src="../BDM_PostArt_V3/js/enlaces.js"></script>
</body>

</html>