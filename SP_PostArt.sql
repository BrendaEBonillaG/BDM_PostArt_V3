USE PostArt;

SHOW PROCEDURE STATUS WHERE Db = 'PostArt';


-- LOGIN
DELIMITER $$

CREATE PROCEDURE `LoginUsuario`(IN p_username VARCHAR(50), IN p_password VARCHAR(255))
BEGIN
    -- Verifica si el usuario existe y la contraseña es correcta
    SELECT ID_Usuario, Nombre, Nickname, Correo, Rol
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

-- CATEGORIAS

DELIMITER $$

CREATE PROCEDURE InsertarCategoria(
    IN p_Nombre VARCHAR(50)
)
BEGIN
    INSERT INTO Categorias (Nombre)
    VALUES (p_Nombre);
END$$

DELIMITER ;

-- PUBLICACIONES

-- PRUEBA
DELIMITER //
CREATE PROCEDURE InsertarPublicacion(
    IN p_usuario VARCHAR(50),
    IN p_categoria INT,
    IN p_titulo VARCHAR(255),
    IN p_contenido TEXT,
    IN p_tipo VARCHAR(50),
    IN p_imagen LONGBLOB
)
BEGIN
    INSERT INTO publicaciones (usuario, categoria, titulo, contenido, tipo, imagen)
    VALUES (p_usuario, p_categoria, p_titulo, p_contenido, p_tipo, p_imagen);
END //
DELIMITER ;

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

CREATE PROCEDURE SP_GestionarDonacion(
    IN p_operacion VARCHAR(10),        -- 'crear' o 'actualizar'
    IN p_Id_Donacion INT,              -- Obligatorio solo si p_operacion = 'actualizar'
    IN p_Id_usuario INT,
    IN p_Id_Categoria INT,
    IN p_Titulo VARCHAR(100),
    IN p_Contenido TEXT,
    IN p_Imagen MEDIUMBLOB,
    IN p_Video_url VARCHAR(255),
    IN p_Meta DECIMAL(10,2),
    IN p_Fecha_Limite TIMESTAMP
)
BEGIN
    IF p_operacion = 'crear' THEN
        INSERT INTO Donaciones (
            Id_usuario, Id_Categoria, Titulo, Contenido,
            Imagen, Video_url, Meta, Fecha_Limite
        ) VALUES (
            p_Id_usuario, p_Id_Categoria, p_Titulo, p_Contenido,
            p_Imagen, p_Video_url, p_Meta, p_Fecha_Limite
        );

    ELSEIF p_operacion = 'actualizar' THEN
        UPDATE Donaciones
        SET
            Id_usuario = p_Id_usuario,
            Id_Categoria = p_Id_Categoria,
            Titulo = p_Titulo,
            Contenido = p_Contenido,
            Imagen = p_Imagen,
            Video_url = p_Video_url,
            Meta = p_Meta,
            Fecha_Limite = p_Fecha_Limite
        WHERE Id_Donacion = p_Id_Donacion;

    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Operación no válida. Use "crear" o "actualizar".';
    END IF;
END$$

DELIMITER ;

DELIMITER $$

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

DELIMITER $$

CREATE PROCEDURE InsertarMensajeGrupal(
    IN p_Id_chat INT,
    IN p_Id_usuario INT,
    IN p_Contenido TEXT
)
BEGIN
    INSERT INTO Mensajes_Grupales (Id_chat, Id_usuario, Contenido)
    VALUES (p_Id_chat, p_Id_usuario, p_Contenido);
END$$

DELIMITER ;


-- CHAT PRIVADO
DELIMITER $$

CREATE PROCEDURE InsertarMensajeDirecto(
    IN p_Id_emisor INT,
    IN p_Id_receptor INT,
    IN p_Contenido TEXT
)
BEGIN
    INSERT INTO Mensajes (Id_emisor, Id_receptor, Contenido, Leido)
    VALUES (p_Id_emisor, p_Id_receptor, p_Contenido, 0);
END$$

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