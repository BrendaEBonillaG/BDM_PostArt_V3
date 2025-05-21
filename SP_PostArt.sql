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

-- DROPS
DROP PROCEDURE VerificarCorreo;
DROP PROCEDURE sp_registrar_usuario;
DROP PROCEDURE  LoginUsuario;