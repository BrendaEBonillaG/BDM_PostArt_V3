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
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/proyecto.css">
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
                <div class="notify-botton-activity-bar">
                    <i class='bx bxs-message-error'></i>
                </div>
                <div class="message-botton-activity-bar">
                    <button onclick="location.href='Chat.php'" class="icon-button">
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
    <!-- posts -->
    <div class="container-picture-dashboard">

        <div class="contenedor_proyecto_publicado">
            <h1>Sube tu proyecto</h1>
            <form action="PHP/guardar_proyecto.php" method="POST" enctype="multipart/form-data">


                <label for="titulo">Título del proyecto</label>
                <input type="text" name="titulo" class="input_donacion" required>
                <label for="categoria">Categoría</label>
                <select name="categoria" class="input_donacion" required id="selectCategoria"></select>


                <label for="imagen">Sube un video</label>
                <input type="file" name="video_url" class="input_donacion"  required>
                

                <label for="imagen">Sube una imagen</label>
                <input type="file" name="imagen" class="input_donacion" accept="image/*" required>

                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" class="input_donacion" rows="5" required></textarea>

                <label for="meta">Monto meta</label>
                <input type="number" name="meta" step="0.01" class="input_donacion" required>

                <label for="fecha_limite">Fecha de cierre</label>
                <input type="date" name="fecha_limite" class="input_donacion" required>

                <button type="submit" class="boton_donacion">Guardar Proyecto</button>
            </form>



        </div>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="../BDM_PostArt_V3/js/script.js"></script>
    <script src="../BDM_PostArt_V3/js/enlaces.js"></script>
    <script src="js/categorias.js"></script>


</body>

</html>