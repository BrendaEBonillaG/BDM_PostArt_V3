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
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/style_container_img.css">
    <title>PostArt | publicar</title>
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


    <div class="container mt-5">
        <h1>Subir Imagen</h1>
        <form method="POST" action="PHP/Up_Imag.php" enctype="multipart/form-data">
            <input type="hidden" name="dato" value="inserta_archivo">

            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ingrese el título"
                    required>

                <label for="descripcion">Descripción:</label>
                <input type="text" class="form-control" name="descripcion" id="descripcion"
                    placeholder="Ingrese descripción" required>

                <label for="tipo">Tipo:</label>
                <select class="form-control" name="tipo" id="tipo" required>
                    <option value="Publica">Pública</option>
                    <option value="Suscripcion">Por Suscripción</option>
                </select>

                <label for="categoria">Categoría:</label>
                <?php
                include('Conexion.php');

                $resultado = $conexion->query("CALL SP_ObtenerCategorias()");

                echo '<select class="form-control" name="categoria" id="categoria" required>';
                while ($fila = $resultado->fetch_assoc()) {
                    echo '<option value="' . $fila['Id_Categoria'] . '">' . htmlspecialchars($fila['Nombre']) . '</option>';
                }
                echo '</select>';

                // Limpieza de resultados múltiples
                while ($conexion->more_results() && $conexion->next_result()) {
                    $extra = $conexion->use_result();
                    if ($extra instanceof mysqli_result) {
                        $extra->free();
                    }
                }
                ?>

            </div>

            <div class="form-group">
                <div class="drop-area" id="dropArea">
                    Arrastre y suelte la imagen aquí o haga clic para seleccionar
                </div>
                <input type="file" class="form-control-file" name="imagen" id="imagen" style="display: none;" required>
                <div class="file-list" id="filelist"></div>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="js/script.js"></script>
    <script src="js/enlaces.js"></script>
    <script src="js/cargar_img.js"></script>
</body>

</html>