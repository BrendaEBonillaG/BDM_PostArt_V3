<?php
session_start();
include("Conexion.php");


if (!isset($_SESSION['usuario'])) {
    header('Location: Login.html');
    exit();
}

$usuario = $_SESSION['usuario'];

if (!empty($usuario['Foto_perfil'])) {
    // Si es binario, codifica; si ya es base64, úsalo
    if (base64_encode(base64_decode($usuario['Foto_perfil'], true)) === $usuario['Foto_perfil']) {
        // Ya viene en base64 (no vuelvas a codificar)
        $fotoPerfilSrc = 'data:image/jpeg;base64,' . $usuario['Foto_perfil'];
    } else {
        // Viene en binario (normal en blobs directos de MySQL)
        $fotoPerfilSrc = 'data:image/jpeg;base64,' . base64_encode($usuario['Foto_perfil']);
    }
} else {
    $fotoPerfilSrc = '../BDM_PostArt_V3/imagenes-prueba/User.jpg';
}



$nickname = $usuario['Nickname'];
$rol = $usuario['Rol'];
$biografia = $usuario['Biografia'] ?? 'Artista sin descripción';


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

// Calcular días restantes correctamente (solo fecha, sin horas)
$fecha_limite = new DateTime($proyecto['Fecha_Limite']);
$hoy = new DateTime();
$hoy = new DateTime($hoy->format('Y-m-d'));  // Normalizamos la fecha de hoy quitando la hora

if ($hoy > $fecha_limite) {
    $dias_restantes = 0;
} else {
    $intervalo = $hoy->diff($fecha_limite);
    $dias_restantes = $intervalo->days;
}



$stmt = $conexion->prepare("CALL SP_ObtenerResumenDonacion(?)");
$stmt->bind_param("i", $idProyecto);
$stmt->execute();

$res = $stmt->get_result();
$row = $res->fetch_assoc();

$recaudado = $row['Recaudado'] ?? 0;
$numero_participantes = $row['Participantes'] ?? 0;
$completado = ($recaudado >= $meta || $dias_restantes <= 0);




$stmt->close();
$conexion->next_result(); // Siempre después de un CALL


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

    <div class="container-picture-dashboard">
        <div class="contenedor_proyecto_publicado">
            <div class="titulo_del_proyecto">
                <h1><?php echo htmlspecialchars($titulo); ?></h1>
                <p class="usuario_del_proyecto">Publicado por: <span><?php echo htmlspecialchars($autor); ?></span></p>
                <p class="categoria_del_proyecto">Categoría:
                    <span><?php echo htmlspecialchars($categoria_nombre); ?></span>
                </p>
            </div>

            <div class="video_del_proyecto">
                <div class="video-wrapper">
                    <?php
                    if ($video_url && file_exists(__DIR__ . '/' . $video_url)) {
                        ?>
                        <video width="100%" height="360" controls preload="metadata">
                            <source src="<?php echo htmlspecialchars($video_url); ?>" type="video/mp4">
                            Tu navegador no soporta el elemento de video.
                        </video>

                        <!-- Debugging info (eliminar en producción) -->
                        <div style="margin-top: 10px; font-size: 12px; color: #666;">
                            <p>Ruta del video: <?php echo htmlspecialchars($video_url); ?></p>
                            <p>Archivo existe: <?php echo file_exists(__DIR__ . '/' . $video_url) ? 'Sí' : 'No'; ?></p>
                            <p>Tamaño del archivo:
                                <?php echo file_exists(__DIR__ . '/' . $video_url) ? filesize(__DIR__ . '/' . $video_url) . ' bytes' : 'N/A'; ?>
                            </p>
                        </div>

                        <?php
                    } else {
                        echo '<p>Video no encontrado. Ruta: ' . htmlspecialchars($video_url) . '</p>';
                        echo '<p>Verificando en: ' . __DIR__ . '/' . $video_url . '</p>';
                    }
                    ?>
                </div>
            </div>


            <div class="imagen_del_proyecto">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($imagen); ?>" class="imagen_proyecto"
                    alt="Imagen del proyecto">
            </div>

            <div class="descripcion_del_proyecto">
                <h2>Descripción</h2>
                <p><?php echo nl2br(htmlspecialchars($descripcion)); ?></p>
            </div>

            <div class="contador_dias">
                <p>Quedan <span id="dias_restantes"><?php echo $dias_restantes; ?></span> días para el cierre del
                    proyecto.</p>
            </div>

            <div class="contador_participantes">
                <p><span id="numero_participantes"><?php echo $numero_participantes; ?></span>
                    personas han apoyado este
                    proyecto.</p>
            </div>

            <div class="monto_donacion">
                <p>Recaudado: <strong>$<?php echo number_format($recaudado, 2); ?></strong> de una meta de
                    <strong>$<?php echo number_format($meta, 2); ?></strong>
                </p>
            </div>


            <div class="barra_donacion">
                <h2>Contribuye al proyecto</h2>

                <?php if ($completado): ?>
                    <button class="boton_donacion" style="background-color: green; cursor: not-allowed;" disabled>
                        Meta alcanzada
                    </button>
                <?php else: ?>
                    <form id="formDonacion">
                        <input type="hidden" id="id_donacion" value="<?php echo $idProyecto; ?>">
                        <input type="hidden" id="id_usuario_artista" value="<?php echo $proyecto['Id_usuario']; ?>">
                        <input type="number" id="monto" placeholder="Cantidad a donar" class="input_donacion" step="0.01"
                            required>
                        <button type="button" id="btnAbrirPago" class="boton_donacion">Donar</button>
                    </form>
                <?php endif; ?>
            </div>




        </div>

    </div>
    <script>
        // Escuchamos el click del botón "Donar"
        document.getElementById("btnAbrirPago").addEventListener("click", () => {
            const monto = document.getElementById("monto").value;
            const id_donacion = document.getElementById("id_donacion").value;
            const id_usuario_artista = document.getElementById("id_usuario_artista").value;

            if (!monto || monto <= 0) {
                alert("Ingrese un monto válido.");
                return;
            }

            // Guardamos los datos temporalmente en localStorage
            localStorage.setItem("montoDonacion", monto);
            localStorage.setItem("id_donacion", id_donacion);
            localStorage.setItem("id_usuario_artista", id_usuario_artista);

            // Abrimos la ventana de pago (Tarjeta.html)
            window.open("Tarjeta.html", "_blank", "width=800,height=600");
        });

        // Esta función será llamada desde la ventana de pago al finalizar
        function actualizarDonacionDesdePago() {
            const idProyecto = <?php echo $idProyecto; ?>;

            fetch("PHP/actualizar_donacion.php?id=" + idProyecto)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    document.getElementById("numero_participantes").innerText = data.participantes;
                    document.querySelector(".monto_donacion strong").innerText = `$${parseFloat(data.recaudado).toFixed(2)}`;

                    document.getElementById("monto").value = "";
                })
                .catch(err => console.error("Error al actualizar:", err));
        }

    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../BDM_PostArt_V3/js/script.js"></script>
    <script src="../BDM_PostArt_V3/js/enlaces.js"></script>

</body>

</html>