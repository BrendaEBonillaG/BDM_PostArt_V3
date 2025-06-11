USE PostArt;

SHOW PROCEDURE STATUS WHERE Db = 'PostArt';

DROP PROCEDURE LoginUsuario;
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


-- USUARIOS DEFINITIVO 
DELIMITER $$

CREATE PROCEDURE GestionUsuario(
    IN p_accion VARCHAR(20),         -- Acción a realizar: 'login', 'registro', 'verificar', 'mostrar', 'actualizar'
    
    -- Parámetros comunes
    IN p_id_usuario INT,
    IN p_nombre VARCHAR(50),
    IN p_apepa VARCHAR(50),
    IN p_apema VARCHAR(50),
    IN p_nickname VARCHAR(50),
    IN p_correo VARCHAR(100),
    IN p_contrasena VARCHAR(255),
    IN p_biografia TEXT,
    IN p_foto MEDIUMBLOB,
    IN p_rol VARCHAR(20),

    OUT p_existe INT -- Para la acción 'verificar'
)
BEGIN
    IF p_accion = 'login' THEN
        SELECT ID_Usuario, Nombre, Nickname, Correo, Rol
        FROM Usuario
        WHERE Nickname = p_nickname AND Contrasena = p_contrasena;

    ELSEIF p_accion = 'registro' THEN
        INSERT INTO Usuario (Nombre, ApePa, ApeMa, Nickname, Correo, Contrasena, Foto_perfil, Biografia, Rol, Estado)
        VALUES (
            p_nombre, 
            COALESCE(p_apepa, 'agregar'), 
            COALESCE(p_apema, 'agregar'), 
            p_nickname, 
            p_correo, 
            p_contrasena, 
            p_foto, 
            COALESCE(p_biografia, 'agregar'), 
            p_rol, 
            'Activo'
        );

    ELSEIF p_accion = 'verificar' THEN
        SELECT COUNT(*) INTO p_existe
        FROM Usuario
        WHERE correo = p_correo;

    ELSEIF p_accion = 'mostrar' THEN
        SELECT ID_Usuario, Nombre, ApePa, ApeMa, Nickname, Correo, Biografia, Foto_perfil, Rol
        FROM Usuario
        WHERE ID_Usuario = p_id_usuario;

    ELSEIF p_accion = 'actualizar' THEN
        UPDATE Usuario
        SET 
            Nombre = p_nombre,
            ApePa = p_apepa,
            ApeMa = p_apema,
            Correo = p_correo,
            Biografia = p_biografia,
            Foto_perfil = IF(p_foto IS NOT NULL AND LENGTH(p_foto) > 0, p_foto, Foto_perfil)
        WHERE ID_Usuario = p_id_usuario;

    END IF;
END$$

DELIMITER ;



-- PUBLICACIONES
-- OFICIAL
DELIMITER $$

CREATE PROCEDURE SP_GestionPublicacion(
    IN p_accion VARCHAR(10), -- 'crear', 'editar', 'eliminar'
    IN p_Id_publicacion INT, -- Para editar o eliminar
    IN p_Id_usuario INT,    -- Para crear
    IN p_Id_Categoria INT,
    IN p_Titulo VARCHAR(100),
    IN p_Contenido TEXT,
    IN p_Imagen MEDIUMBLOB,
    IN p_Tipo VARCHAR(20),
    IN p_Estado VARCHAR(20) -- Para editar (por ejemplo 'Activo' o 'Inactivo')
)
BEGIN
    IF p_accion = 'crear' THEN
        INSERT INTO Publicaciones (
            Id_usuario,
            Id_Categoria,
            Titulo,
            Contenido,
            Imagen,
            Tipo,
            Estado
        ) VALUES (
            p_Id_usuario,
            p_Id_Categoria,
            p_Titulo,
            p_Contenido,
            p_Imagen,
            p_Tipo,
            'Activo'
        );
    ELSEIF p_accion = 'editar' THEN
        UPDATE Publicaciones
        SET
            Id_Categoria = p_Id_Categoria,
            Titulo = p_Titulo,
            Contenido = p_Contenido,
            Imagen = p_Imagen,
            Tipo = p_Tipo,
            Estado = p_Estado
        WHERE Id_publicacion = p_Id_publicacion;
    ELSEIF p_accion = 'eliminar' THEN
        UPDATE Publicaciones
        SET Estado = 'Inactivo'
        WHERE Id_publicacion = p_Id_publicacion;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Accion no valida';
    END IF;
END$$

DELIMITER ;

-- DONACIONES


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

DROP PROCEDURE SP_InsertarProyecto;
DELIMITER //
CREATE PROCEDURE SP_InsertarProyecto (
    IN p_id_usuario INT,
    IN p_id_categoria INT,
    IN p_titulo VARCHAR(255),
    IN p_descripcion TEXT,
    IN p_imagen LONGBLOB,
    IN p_video_url VARCHAR(255),
    IN p_meta DECIMAL(10,2),
    IN p_fecha_limite DATE
)
BEGIN
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
        p_id_usuario,
        p_id_categoria,
        p_titulo,
        p_descripcion,
        p_imagen,
        p_video_url,
        p_meta,
        p_fecha_limite
    );
    
    SELECT LAST_INSERT_ID() AS id_proyecto;
END //
DELIMITER ;


--------------------------------------------------------------------
SELECT 
    d.Id_Donacion,
    u.Nickname,
    c.Nombre AS Categoria,
    d.Titulo,
    d.Meta,
    d.Video_url,
    d.Fecha_Limite
FROM Donaciones d
JOIN Usuario u ON d.Id_usuario = u.ID_Usuario
JOIN Categorias c ON d.Id_Categoria = c.Id_Categoria
ORDER BY d.Id_Donacion DESC;
-----------------------------------------------------------------------
DELIMITER $$

-- DONADOR
CREATE PROCEDURE SP_InsertarDonador(
    IN p_Id_usuario_donante INT,
    IN p_Id_usuario_artista INT,
    IN p_Monto DECIMAL(10,2),
    IN p_Id_donacion INT
)
BEGIN
    INSERT INTO Donadores (
        Id_usuario_donante,
        Id_usuario_artista,
        Monto,
        Id_donacion
    ) VALUES (
        p_Id_usuario_donante,
        p_Id_usuario_artista,
        p_Monto,
        p_Id_donacion
    );
END$$

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE VerificarMetaDonacion (IN artista_id INT)
BEGIN
  DECLARE recaudado DECIMAL(10,2);
  DECLARE meta DECIMAL(10,2);

  SELECT IFNULL(SUM(Monto), 0) INTO recaudado
  FROM Donadores
  WHERE Id_usuario_artista = artista_id;

  SELECT Meta INTO meta
  FROM Donaciones
  WHERE Id_usuario = artista_id
  ORDER BY Fecha_publicacion DESC
  LIMIT 1;

  IF recaudado >= meta THEN
    UPDATE Donaciones
    SET Estado = 'Cumplida'
    WHERE Id_usuario = artista_id
    AND Estado != 'Cumplida';
  END IF;
END$$

DELIMITER ;

-- COMENTARIOS
DELIMITER $$

CREATE PROCEDURE RegistrarComentario (
    IN p_Id_publicacion INT,
    IN p_Id_usuario INT,
    IN p_Comentario TEXT
)
BEGIN
    INSERT INTO Comentarios (Id_publicacion, Id_usuario, Comentario)
    VALUES (p_Id_publicacion, p_Id_usuario, p_Comentario);
END$$

DELIMITER ;

-- SEGUIDORES
DROP PROCEDURE IF EXISTS SeguirArtista;

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

DELIMITER ;




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



DELIMITER $$

CREATE PROCEDURE GestionarSeguimiento (
    IN p_Id_usuario_seguidor INT,
    IN p_Id_usuario_artista INT,
    IN p_Accion VARCHAR(20) -- 'seguir' o 'cancelar'
)
BEGIN
    DECLARE v_Existe INT;

    -- Verificar si ya existe un registro de seguimiento entre estos usuarios
    SELECT COUNT(*) INTO v_Existe
    FROM Seguidores
    WHERE Id_usuario_seguidor = p_Id_usuario_seguidor
      AND Id_usuario_artista = p_Id_usuario_artista;

    IF p_Accion = 'seguir' THEN
        IF v_Existe = 0 THEN
            -- Insertar nuevo seguimiento si no existe
            INSERT INTO Seguidores (Id_usuario_seguidor, Id_usuario_artista, Estado)
            VALUES (p_Id_usuario_seguidor, p_Id_usuario_artista, 'Activo');
        ELSE
            -- Si ya existía, actualizar estado a 'Activo'
            UPDATE Seguidores
            SET Estado = 'Activo',
                Fecha_inicio = CURRENT_TIMESTAMP
            WHERE Id_usuario_seguidor = p_Id_usuario_seguidor
              AND Id_usuario_artista = p_Id_usuario_artista;
        END IF;
    
    ELSEIF p_Accion = 'cancelar' THEN
        -- Actualizar el estado a 'Cancelado' si la relación existe
        UPDATE Seguidores
        SET Estado = 'Cancelado'
        WHERE Id_usuario_seguidor = p_Id_usuario_seguidor
          AND Id_usuario_artista = p_Id_usuario_artista
          AND Estado = 'Activo';
    END IF;
END$$

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


-- REDES SOCIALES
DELIMITER $$

CREATE PROCEDURE GestionarRedSocial(
    IN p_Accion VARCHAR(20),         -- 'insertar' o 'actualizar'
    IN p_Id_red INT,                 -- Usado solo para actualizar
    IN p_Id_usuario INT,
    IN p_Nombre VARCHAR(50),
    IN p_Link VARCHAR(255)
)
BEGIN
    IF p_Accion = 'insertar' THEN
        INSERT INTO Redes_sociales (Id_usuario, Nombre, Link)
        VALUES (p_Id_usuario, p_Nombre, p_Link);

    ELSEIF p_Accion = 'actualizar' THEN
        UPDATE Redes_sociales
        SET Nombre = p_Nombre,
            Link = p_Link
        WHERE Id_red = p_Id_red
          AND Id_usuario = p_Id_usuario;
    END IF;
END$$

DELIMITER ;

-- LIKES
DELIMITER $$

CREATE PROCEDURE GestionarMeGusta(
    IN p_Accion VARCHAR(20),         -- 'insertar' o 'actualizar'
    IN p_Id_Like INT,                -- Se usa solo para actualizar
    IN p_Id_usuario INT,
    IN p_Id_publicacion INT
)
BEGIN
    IF p_Accion = 'insertar' THEN
        INSERT INTO Me_Gusta (Id_usuario, Id_publicacion)
        VALUES (p_Id_usuario, p_Id_publicacion);

    ELSEIF p_Accion = 'actualizar' THEN
        UPDATE Me_Gusta
        SET Id_usuario = p_Id_usuario,
            Id_publicacion = p_Id_publicacion
        WHERE Id_Like = p_Id_Like;
    END IF;
END$$

DELIMITER ;

-- CHATS GRUPALES
DELIMITER $$

CREATE PROCEDURE InsertarChatGrupal(
    IN p_Id_usuario_artista INT,
    IN p_Nombre_chat VARCHAR(100)
)
BEGIN
    INSERT INTO Chats_Grupales (Id_usuario_artista, Nombre_chat)
    VALUES (p_Id_usuario_artista, p_Nombre_chat);
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE InsertarMiembroChat(
    IN p_Id_usuario INT,
    IN p_Id_chat INT
)
BEGIN
    INSERT INTO Miembros_Chat (Id_usuario, Id_chat)
    VALUES (p_Id_usuario, p_Id_chat);
END$$

DELIMITER ;

DROP PROCEDURE ChatGrupal_Operacion;
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


DELIMITER //
CREATE PROCEDURE ObtenerChatsGrupalesDeUsuario(IN p_id_usuario INT)
BEGIN
    SELECT cg.id_chat, cg.nombre, cg.imagen
    FROM Chat_Grupal cg
    INNER JOIN Participantes_Grupal pg ON cg.id_chat = pg.id_ChatGrupal
    WHERE pg.id_usuario = p_id_usuario;
END //
DELIMITER ;


-- CHAT PRIVADO
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

CREATE PROCEDURE SP_VerificarUsuario(
    IN p_usuario VARCHAR(255)
)
BEGIN
    SELECT 1 FROM Usuarios WHERE usuario = p_usuario LIMIT 1;
END //

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



DROP PROCEDURE SP_ObtenerCategorias;
DROP PROCEDURE IF EXISTS SP_ObtenerCategorias;

DELIMITER //

CREATE PROCEDURE SP_ObtenerCategorias()
BEGIN
    SELECT Id_Categoria, Nombre
    FROM Categorias;
END //

DELIMITER ;






DELIMITER //

CREATE PROCEDURE SP_InsertarPublicacion (
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
END //

DELIMITER ;


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

-- INSERTAR Y LLAMAR
DROP PROCEDURE ChatPrivado_Operacion;
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




-- SUBSCRIPCIONES
DELIMITER $$

CREATE PROCEDURE InsertarOActualizarSubscripcion(
    IN p_Id_subscripcion INT,
    IN p_Id_usuario_comprador INT,
    IN p_Id_usuario_artista INT,
    IN p_Monto DECIMAL(10,2),
    IN p_Fecha_fin DATE,
    IN p_Estado VARCHAR(20)
)
BEGIN
    IF p_Id_subscripcion IS NULL OR p_Id_subscripcion = 0 THEN
        -- Insertar nueva subscripción
        INSERT INTO Subscripciones (
            Id_usuario_comprador,
            Id_usuario_artista,
            Monto,
            Fecha_fin,
            Estado
        ) VALUES (
            p_Id_usuario_comprador,
            p_Id_usuario_artista,
            p_Monto,
            p_Fecha_fin,
            p_Estado
        );
    ELSE
        -- Actualizar subscripción existente
        UPDATE Subscripciones
        SET 
            Monto = p_Monto,
            Fecha_fin = p_Fecha_fin,
            Estado = p_Estado
        WHERE Id_subscripcion = p_Id_subscripcion;
    END IF;
END$$

DELIMITER ;



-- DROPS
DROP PROCEDURE VerificarCorreo;
DROP PROCEDURE sp_registrar_usuario;
DROP PROCEDURE  LoginUsuario;