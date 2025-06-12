USE PostArt;
-- USUARIOS _____________________________________________________________________________________

-- LOGIN
DROP PROCEDURE IF EXISTS LoginUsuario;

DELIMITER $$

CREATE PROCEDURE `LoginUsuario`(
    IN p_username VARCHAR(50), 
    IN p_password VARCHAR(255)
)
BEGIN
    SELECT 
        ID_Usuario, 
        Nombre, 
        Nickname, 
        Correo, 
        Rol,
        Biografia,
        Foto_perfil
    FROM Usuario
    WHERE Nickname = p_username AND Contrasena = p_password;
END$$

DELIMITER ;



-- REGISTRO
DELIMITER $$

CREATE PROCEDURE RegistrarUsuario(
    IN p_Nombre VARCHAR(50),
    IN p_Nickname VARCHAR(50),
    IN p_Correo VARCHAR(100),
    IN p_Contrasena VARCHAR(255),
    IN p_Rol VARCHAR(20),
    IN p_Foto_perfil MEDIUMBLOB
)
BEGIN
    INSERT INTO Usuario (Nombre, ApePa, ApeMa, Nickname, Correo, Contrasena, Foto_perfil, Biografia, Rol, Estado)
    VALUES (
        p_Nombre, 
        'agregar',  -- Para ApePa
        'agregar',  -- Para ApeMa
        p_Nickname, 
        p_Correo, 
        p_Contrasena, 
        p_Foto_perfil, 
        'agregar',  -- Para Biografia
        p_Rol, 
        'Activo'
    );
END$$

DELIMITER ;

-- VERIFICAR CORREO
DELIMITER $$

CREATE PROCEDURE VerificarCorreo(
    IN correo_param VARCHAR(255),
    OUT existe INT
)
BEGIN
    SELECT COUNT(*) INTO existe
    FROM Usuario
    WHERE correo = correo_param;
END$$

DELIMITER ;


-- MOSTRAR USUARIO

DELIMITER $$

CREATE PROCEDURE `GetUserProfileInfo`(IN p_id_usuario INT)
BEGIN
    SELECT ID_Usuario, Nombre, ApePa, ApeMa, Nickname, Correo, Biografia, Foto_perfil, Rol
    FROM Usuario
    WHERE ID_Usuario = p_id_usuario;
END$$

DELIMITER ;


-- ACTUALIZAR

DELIMITER $$

CREATE PROCEDURE `UpdateUserProfileInfo`(
    IN p_id_usuario INT,
    IN p_nombre VARCHAR(50),
    IN p_apepa VARCHAR(50),
    IN p_apema VARCHAR(50),
    IN p_correo VARCHAR(100),
    IN p_biografia TEXT,
    IN p_foto MEDIUMBLOB  -- o MEDIUMBLOB si prefieres mantenerlo igual
)
BEGIN
    UPDATE Usuario
    SET 
        Nombre = p_nombre,
        ApePa = p_apepa,
        ApeMa = p_apema,
        Correo = p_correo,
        Biografia = p_biografia,
        Foto_perfil = IF(p_foto IS NOT NULL AND LENGTH(p_foto) > 0, p_foto, Foto_perfil) -- Solo si se envía una imagen nueva
    WHERE ID_Usuario = p_id_usuario;
END$$

DELIMITER ;


DELIMITER //
CREATE PROCEDURE `ObtenerDatosUsuario`(IN p_id_usuario INT)
BEGIN
    SELECT Nickname, Rol, Biografia, Foto_perfil
    FROM Usuario
    WHERE ID_Usuario = p_id_usuario;
END//
DELIMITER  ;

DELIMITER //
CREATE PROCEDURE `ObtenerDatosPerfilArtista`(IN p_id_artista INT)
BEGIN
    SELECT 
        u.Nickname, u.Rol, u.Biografia, u.Foto_perfil, u.Correo,
        rs1.Link AS Facebook,
        rs2.Link AS Instagram,
        rs3.Link AS Twitter,
        rs4.Link AS Youtube
    FROM Usuario u
    LEFT JOIN Redes_sociales rs1 ON u.ID_Usuario = rs1.Id_usuario AND rs1.Nombre = 'Facebook'
    LEFT JOIN Redes_sociales rs2 ON u.ID_Usuario = rs2.Id_usuario AND rs2.Nombre = 'Instagram'
    LEFT JOIN Redes_sociales rs3 ON u.ID_Usuario = rs3.Id_usuario AND rs3.Nombre = 'Twitter'
    LEFT JOIN Redes_sociales rs4 ON u.ID_Usuario = rs4.Id_usuario AND rs4.Nombre = 'Youtube'
    WHERE u.ID_Usuario = p_id_artista;
END//
DELIMITER ;

-- SEGUIDORES _______________________________________________________________________________________________________

DELIMITER //

CREATE PROCEDURE ObtenerTotalSeguidores (
    IN p_id_artista INT
)
BEGIN
    SELECT COUNT(*) AS total_seguidores
    FROM Seguidores
    WHERE Id_usuario_artista = p_id_artista;
END;
//

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SeguirArtista(
    IN p_id_seguidor INT,
    IN p_id_artista INT
)
fin: BEGIN
    -- Validación: no puede seguirse a sí mismo
    IF p_id_seguidor = p_id_artista THEN
        SELECT 'error_mismo_usuario' AS resultado;
        LEAVE fin;
    END IF;

    -- Validación: verificar si ya lo sigue
    IF EXISTS (
        SELECT 1 
        FROM Seguidores 
        WHERE Id_usuario_seguidor = p_id_seguidor AND Id_usuario_artista = p_id_artista
    ) THEN
        SELECT 'ya' AS resultado;
        LEAVE fin;
    END IF;

    -- Insertar nuevo seguimiento
    INSERT INTO Seguidores (Id_usuario_seguidor, Id_usuario_artista)
    VALUES (p_id_seguidor, p_id_artista);

    SELECT 'ok' AS resultado;
END;
//

-- CHATS _______________________________________________________________________________________________

DELIMITER //
CREATE PROCEDURE ObtenerUsuariosYChatsPrivados(IN p_id_usuario INT)
BEGIN
  SELECT 
    u.ID_Usuario AS id_usuario,
    u.Nickname AS nombreUsu,
    u.Foto_perfil AS foto,
    cp.id_chat
  FROM Usuario u
  JOIN Chat_Privado cp 
    ON ((u.ID_Usuario = cp.id_remitente AND cp.id_emisor = p_id_usuario)
     OR (u.ID_Usuario = cp.id_emisor AND cp.id_remitente = p_id_usuario))
  WHERE u.ID_Usuario != p_id_usuario
  GROUP BY u.ID_Usuario, cp.id_chat;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE ObtenerChatsGrupalesDeUsuario(IN p_id_usuario INT)
BEGIN
    SELECT cg.id_chat, cg.nombre, cg.imagen
    FROM Chat_Grupal cg
    INNER JOIN Participantes_Grupal pg ON cg.id_chat = pg.id_ChatGrupal
    WHERE pg.id_usuario = p_id_usuario;
END //
DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_ObtenerChatPrivado (
    IN rem1 INT,
    IN em1 INT,
    IN rem2 INT,
    IN em2 INT
)
BEGIN
    SELECT id_chat FROM Chat_Privado 
    WHERE (id_remitente = rem1 AND id_emisor = em1)
       OR (id_remitente = rem2 AND id_emisor = em2)
    LIMIT 1;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_CrearChatPrivado (
    IN remitente INT,
    IN emisor INT
)
BEGIN
    INSERT INTO Chat_Privado (id_remitente, id_emisor)
    VALUES (remitente, emisor);
END //

DELIMITER ;

DELIMITER //
CREATE PROCEDURE ChatPrivado_Operacion(
    IN p_operacion VARCHAR(10), -- 'insertar' o 'listar'
    IN p_id_chat INT,
    IN p_id_usuario INT,
    IN p_contenido TEXT
)
BEGIN
    IF p_operacion = 'insertar' THEN
        INSERT INTO Mensajes_Privado (id_chat_Privado, id_usuario, contenido)
        VALUES (p_id_chat, p_id_usuario, p_contenido);

    ELSEIF p_operacion = 'listar' THEN
        SELECT mp.id_usuario, u.Nickname AS nombre_usuario, mp.contenido, mp.fecha_envio
        FROM Mensajes_Privado mp
        INNER JOIN Usuario u ON mp.id_usuario = u.ID_Usuario
        WHERE mp.id_chat_Privado = p_id_chat
        ORDER BY mp.fecha_envio ASC;
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE ChatGrupal_Operacion(
    IN p_operacion VARCHAR(10), -- 'insertar' o 'listar'
    IN p_id_chat INT,
    IN p_id_usuario INT,
    IN p_contenido TEXT
)
BEGIN
    IF p_operacion = 'insertar' THEN
        INSERT INTO Mensajes_Grupales (id_chat_Grupal, id_usuario, contenido)
        VALUES (p_id_chat, p_id_usuario, p_contenido);

    ELSEIF p_operacion = 'listar' THEN
        SELECT 
            mg.id_usuario, 
            u.Nickname AS nombre_usuario, 
            mg.contenido, 
            mg.fecha_envio
        FROM Mensajes_Grupales mg
        INNER JOIN Usuario u ON mg.id_usuario = u.ID_Usuario
        WHERE mg.id_chat_Grupal = p_id_chat
        ORDER BY mg.fecha_envio ASC;
    END IF;
END //
DELIMITER ;

-- PUBLICACIONES ________________________________________________________________________________________

DELIMITER //
CREATE PROCEDURE `SP_InsertarPublicacion`(
    IN p_id_usuario INT,
    IN p_id_categoria INT,
    IN p_titulo VARCHAR(255),
    IN p_contenido TEXT,
    IN p_imagen LONGBLOB,
    IN p_tipo VARCHAR(50)
)
BEGIN
    INSERT INTO Publicaciones (
        Id_usuario,
        Id_Categoria,
        Titulo,
        Contenido,
        Imagen,
        Tipo,
        Fecha_creacion,
        Estado
    ) VALUES (
        p_id_usuario,
        p_id_categoria,
        p_titulo,
        p_contenido,
        p_imagen,
        p_tipo,
        NOW(),
        'Activo'
    );
END
DELIMITER ;
DELIMITER //

CREATE PROCEDURE SP_ObtenerPublicacionesPorUsuario(
    IN p_id_usuario INT
)
BEGIN
    SELECT Imagen 
    FROM Publicaciones 
    WHERE id_usuario = p_id_usuario 
    ORDER BY Fecha_creacion DESC;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_ObtenerPublicacionesActivas()
BEGIN
    SELECT 
        p.Id_publicacion, 
        p.Titulo, 
        p.Imagen, 
        u.ID_Usuario, 
        u.Foto_perfil, 
        u.Nombre, 
        u.Rol
    FROM Publicaciones p 
    JOIN Usuario u ON p.ID_Usuario = u.ID_Usuario 
    WHERE p.Estado = 'Activo' 
    ORDER BY p.Fecha_creacion DESC;
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE SP_ObtenerPublicacionPorID(IN p_id INT)
BEGIN
    SELECT p.Titulo, p.Imagen, u.Foto_perfil, u.Nickname, u.Rol
    FROM Publicaciones p
    JOIN Usuario u ON p.ID_Usuario = u.ID_Usuario
    WHERE p.Id_publicacion = p_id;
END //

DELIMITER ;

-- DONACIONES ________________________________________________________________________________________________

DELIMITER //
CREATE PROCEDURE SP_ObtenerProyectoCompleto(IN p_id INT)
BEGIN
    SELECT d.Titulo, d.Contenido, d.Video_url, d.Imagen, d.Meta, d.Fecha_Limite,
           c.Nombre AS Categoria, u.Nickname AS Usuario
    FROM Donaciones d
    JOIN Categorias c ON d.Id_Categoria = c.Id_Categoria
    JOIN Usuario u ON d.Id_usuario = u.ID_Usuario
    WHERE d.Id_Donacion = p_id;
END //
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE SP_InsertarDonacion(
    IN p_Id_usuario INT,
    IN p_Id_Categoria INT, -- ← ahora espera el ID directamente
    IN p_titulo VARCHAR(100),
    IN p_contenido TEXT,
    IN p_imagen MEDIUMBLOB,
    IN p_video_url VARCHAR(255),
    IN p_meta DECIMAL(10,2),
    IN p_fecha_limite DATE
)
BEGIN
    -- Ya no necesitas buscar ni insertar categoría
    INSERT INTO Donaciones (
        Id_usuario,
        Id_Categoria,
        Titulo,
        Contenido,
        Imagen,
        Video_url,
        Meta,
        Fecha_Limite
    ) VALUES (
        p_Id_usuario,
        p_Id_Categoria,
        p_titulo,
        p_contenido,
        p_imagen,
        p_video_url,
        p_meta,
        p_fecha_limite
    );
END$$

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_InsertarDonador;

DELIMITER //

CREATE PROCEDURE SP_InsertarDonador (
    IN p_id_usuario_donante INT,
    IN p_id_usuario_artista INT,
    IN p_id_donacion INT,
    IN p_monto DECIMAL(10,2)
)
BEGIN
    INSERT INTO Donadores (
        Id_usuario_donante,
        Id_usuario_artista,
        Id_donacion,
        Monto,
        Fecha_donacionSP_ObtenerProyectoCompleto
    ) VALUES (
        p_id_usuario_donante,
        p_id_usuario_artista,
        p_id_donacion,
        p_monto,
        NOW()
    );
END //

DELIMITER ;

DROP PROCEDURE IF EXISTS SP_ObtenerProyectoCompleto;
DELIMITER //

CREATE PROCEDURE `SP_ObtenerProyectoCompleto`(IN p_id INT)
BEGIN
    SELECT d.Id_usuario, d.Titulo, d.Contenido, d.Video_url, d.Imagen, d.Meta, d.Fecha_Limite,
           c.Nombre AS Categoria, u.Nickname AS Usuario
    FROM Donaciones d
    JOIN Categorias c ON d.Id_Categoria = c.Id_Categoria
    JOIN Usuario u ON d.Id_usuario = u.ID_Usuario
    WHERE d.Id_Donacion = p_id;
END //

DELIMITER ;


-- CATEGORIAS ____________________________________________________________________________________________


DELIMITER //

CREATE PROCEDURE SP_ObtenerCategorias()
BEGIN
    SELECT Id_Categoria, Nombre
    FROM Categorias;
END //

DELIMITER ;