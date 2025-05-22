<?php
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    // Ojo, la clave en el array es 'Nickname' (mayúscula N)
    echo "Bienvenido, " . $usuario['Nickname'];
} else {
    // No imprimir nada antes del header
    header('Location: ../Login.html');
    exit();
}
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
                <div class="notify-botton-activity-bar">
                    <i class='bx bxs-message-error'></i>
                </div>
                <div class="message-botton-activity-bar">
                    <button onclick="location.href='chat.html'" class="icon-button">
                        <i class='bx bxs-message-minus'></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    <!-- boton menu -->
    <div class="avatar-boton-card" id="botonAvatarMenujs">
        <div class="avatar-image">
            <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg">
        </div>
        <div class="perfile-avatar-status"></div>
    </div>
    <!-- menu perfil -->
    <div class="menu-avatar oculto" id="menuAvatarjs">
        <div class="avatar-menu">
            <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg" alt="">
        </div>
        <div class="content-menu-perfil">
            <div class="menu-perfil-nametag">
                <h3>Jane Doe</h3>
                <h5>2D artist</h5>
                <h6>An artist makes dreams real</h6>
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
            <span><i class='bx bxs-cog'></i></span>
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
                    <img src="/../BDM_PostArt_V3/imagenes-prueba/User.jpg" alt="">
                </div>
                <div class="content-perfil-publicar-info-user">
                    <h2>Jane Doe</h2>
                    <h4>An artist makes dreams real</h4>
                </div>
            </div>
            <div class="add-homeBtn">
                <button onclick="location.href='index.php'" class="icon-button">
                    <i class='bx bxs-home'></i>
                </button>
            </div>
        </div>
        <div class="center-space-zone">
            <form id="formPublicacion" action="PHP/CrearPubli.php" method="POST" enctype="multipart/form-data">
                <div class="upload-space-zone" id="dropZone">
                    <h2>Drag and drop your next masterpiece</h2>
                    <i class='bx bxs-download'></i>
                    <h6>Format: JPG, PNG</h6>
                    <img id="previewImage" style="max-width: 100%; display:none; margin-top:10px; border-radius: 5px;">
                    <!-- input file oculto para subir imagen -->
                    <input type="file" name="imagen" id="fileInput" accept="image/png, image/jpeg" style="display:none;"
                        required>
                </div>
                <!-- Campos restantes del form a la derecha... -->
                <input type="hidden" name="categoria" id="hiddenCategoria">
                <input type="hidden" name="titulo" id="hiddenTitulo">
                <input type="hidden" name="contenido" id="hiddenContenido">
                <input type="hidden" name="tipo" id="hiddenTipo">
            </form>
        </div>



        <div class="space-container-area">
            <div class="left-space-zone">
                <!-- Sin cambios -->
            </div>
            <div class="center-space-zone">
                <!-- Sin cambios -->
            </div>

            <div class="right-space-zone">
                <div class="upload-space-zone-info">
                    <h2 class="upload-space-zone-title">New Post</h2>
                    <div class="upload-space-zone-title-form-container">
                        <select id="categoriaSelect" class="upload-space-zone-name-label" required>
                            <option value="" disabled selected>Seleccione categoría</option>
                            <option value="1">Arte</option>
                            <option value="2">Fotografía</option>
                            <option value="3">Diseño</option>
                        </select>

                        <input type="text" id="tituloInput" placeholder="Name" class="upload-space-zone-name-label"
                            required>

                        <textarea id="contenidoInput" placeholder="Description"
                            class="upload-space-zone-description-label"></textarea>

                        <select id="tipoSelect" class="upload-space-zone-name-label" required
                            style="margin-top:10px; margin-bottom:10px;">
                            <option value="" disabled selected>Tipo de publicación</option>
                            <option value="Publica">Pública</option>
                            <option value="Subscripción">Subscripción</option>
                        </select>
                    </div>
                    <button id="btnCreate" class="upload-space-zone-create-button">Create</button>
                </div>
            </div>

        </div>


    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="../BDM_PostArt_V3/js/script.js"></script>
    <script src="../BDM_PostArt_V3/js/enlaces.js"></script>
    <script src="../BDM_PostArt_V3/js/dragDrog.js"></script>
</body>

</html>