
<?php
include("Conexion.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de proyecto no válido.");
}

$idProyecto = intval($_GET['id']);

// Llamar al SP
$stmt = $conexion->prepare("CALL SP_ObtenerProyectoCompleto(?)");
$stmt->bind_param("i", $idProyecto);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Proyecto no encontrado.");
}

$proyecto = $res->fetch_assoc();
$titulo = $proyecto['Titulo'];
$autor = $proyecto['Usuario'];
$categoria_nombre = $proyecto['Categoria'];
$video_url = $proyecto['Video_url'];
$imagen = $proyecto['Imagen'];
$descripcion = $proyecto['Contenido'];
$meta = $proyecto['Meta'];
$numero_participantes = 0;

$stmt->close();
$conexion->next_result(); // Para liberar el SP

// Calcular días restantes
$fecha_limite = new DateTime($proyecto['Fecha_Limite']);
$hoy = new DateTime();
$dias_restantes = $hoy->diff($fecha_limite)->format('%r%a');

// Consulta del monto recaudado
$stmt = $conexion->prepare("SELECT SUM(Monto) AS Recaudado FROM Donadores WHERE Id_donacion = ?");
$stmt->bind_param("i", $idProyecto);
$stmt->execute();
$stmt->bind_result($recaudado);
$stmt->fetch();
$stmt->close();

$recaudado = $recaudado ?? 0;

// Función para transformar a embed
function obtenerEmbedYouTube($url)
{
    if (strpos($url, 'watch?v=') !== false) {
        parse_str(parse_url($url, PHP_URL_QUERY), $vars);
        return 'https://www.youtube.com/embed/' . $vars['v'];
    }

    if (strpos($url, 'youtu.be/') !== false) {
        $id = explode('youtu.be/', $url)[1];
        $id = strtok($id, '?');
        return 'https://www.youtube.com/embed/' . $id;
    }

    if (strpos($url, 'youtube.com/embed/') !== false) {
        return $url;
    }

    return '';
}
$video_embed_url = obtenerEmbedYouTube($video_url);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>PostArt | Proyecto</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/stylex.css">
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/header.css">
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/navegador.css">
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/cartas.css">
    <link rel="stylesheet" href="../BDM_PostArt_V3/CSS/proyecto.css">

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

    <div class="container-picture-dashboard">
        <div class="contenedor_proyecto_publicado">
            <div class="titulo_del_proyecto">
                <h1><?php echo htmlspecialchars($titulo); ?></h1>
                <p class="usuario_del_proyecto">Publicado por: <span><?php echo htmlspecialchars($autor); ?></span></p>
                <p class="categoria_del_proyecto">Categoría: <span><?php echo htmlspecialchars($categoria_nombre); ?></span></p>
            </div>

            <div class="video_del_proyecto">
                <div class="video-wrapper">
                    <iframe src="<?= $video_embed_url ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>


            <div class="imagen_del_proyecto">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($imagen); ?>" class="imagen_proyecto" alt="Imagen del proyecto">
            </div>

            <div class="descripcion_del_proyecto">
                <h2>Descripción</h2>
                <p><?php echo nl2br(htmlspecialchars($descripcion)); ?></p>
            </div>

            <div class="contador_dias">
                <p>Quedan <span id="dias_restantes"><?php echo $dias_restantes; ?></span> días para el cierre del proyecto.</p>
            </div>

            <div class="contador_participantes">
                <p><span id="numero_participantes"><?php echo $numero_participantes; ?></span> personas han apoyado este proyecto.</p>
            </div>

            <div class="monto_donacion">
                <p>Recaudado: <strong>$<?php echo number_format($recaudado, 2); ?></strong> de una meta de <strong>$<?php echo number_format($meta, 2); ?></strong></p>
            </div>


            <div class="barra_donacion">
                <h2>Contribuye al proyecto</h2>
                <input type="number" placeholder="Cantidad a donar" class="input_donacion">
                <button class="boton_donacion">Donar</button>
            </div>

        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../BDM_PostArt_V3/js/script.js"></script>
    <script src="../BDM_PostArt_V3/js/enlaces.js"></script>
</body>

</html>