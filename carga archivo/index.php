<?php include ('conexion.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="CSS/stylex.css">
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/navegador.css">
    <link rel="stylesheet" href="CSS/cartas.css">
    <title>PostArt | Home</title>
</head>
<body>
    <header>
        <div class="base-header">
            <!-- barra de logo -->
             <div class="base-header-logo">
                <img src="imagenes-prueba/logo1.png" alt="">
             </div>
            <!-- barra de busqueda -->
            <div class="container-base-header-search-bar">
                <input type="text" class="search-bar-dashboard" placeholder="Search...">
            </div>
            <!-- barra de notificaciones -->
            <div class="activity-header-bar">
                <div class="notify-botton-activity-bar">
                    <i class='bx bxs-message-error' ></i>
                </div>
                <div class="message-botton-activity-bar">
                    <button onclick="location.href='chat.html'" class="icon-button">
                        <i class='bx bxs-message-minus' ></i>
                    </button>
                </div>
             </div>
        </div>
    </header>
<!-- boton menu -->
    <div class="avatar-boton-card" id="botonAvatarMenujs">
        <div class="avatar-image">
            <img src="/imagenes-prueba/User.jpg">
        </div>
        <div class="perfile-avatar-status"></div>
    </div>
<!-- menu perfil -->
    <div class="menu-avatar oculto" id="menuAvatarjs">
        <div class="avatar-menu">
            <img src="/imagenes-prueba/User.jpg" alt="">
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
                        <i class='bx bx-menu' ></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="btns-menu-profile">
            <span><i class='bx bxs-user' ></i></span>
            <span><i class='bx bxs-hot menu-favoritos'></i></span>
            <span><i class='bx bxs-add-to-queue'></i></span>
            <span><i class='bx bxs-cog' ></i></span>
            <span><i class='bx bx-log-out' ></i></span>
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
        <?php
        // Consulta para obtener publicaciones activas
        $sql = "SELECT Id_publicacion, Titulo, Imagen FROM Publicaciones WHERE Estado = 'Activo' ORDER BY Fecha_creacion DESC";
        $resultado = $conexion->query($sql);

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                // Convertimos el blob de imagen a base64
                $imagenCodificada = base64_encode($fila['Imagen']);
                $src = 'data:image/jpeg;base64,' . $imagenCodificada;

                echo '
                <div class="card-image-post">
                    <div class="tag-artist-info">
                        <div class="tag-artist-avatar">
                            <img src="/imagenes-prueba/User.jpg">  
                        </div>
                        <div class="tag-artist-name">
                            <h3>Blackat</h3>
                            <h6>2D artist</h6>
                        </div>
                    </div>
                    <div class="tag-paw-botton paw-button">
                        <i class="bx bxs-hot"></i>
                    </div>
                    <div class="imag" id="cardImagePost">
                        <img src="' . $src . '" alt="' . htmlspecialchars($fila['Titulo']) . '">
                    </div>
                </div>';
            }
        } else {
            echo "<p>No hay publicaciones disponibles.</p>";
        }
        ?>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="js/script.js"></script>
    <script src="js/enlaces.js"></script>
</body>
</html>