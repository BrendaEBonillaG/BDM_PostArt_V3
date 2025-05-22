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


-- INSERTAR PUBLICACION
DELIMITER $$

CREATE PROCEDURE InsertarPublicacion(
    IN p_Id_usuario INT,
    IN p_Id_Categoria INT,
    IN p_Titulo VARCHAR(100),
    IN p_Contenido TEXT,
    IN p_Imagen MEDIUMBLOB,
    IN p_Tipo VARCHAR(20)
)
BEGIN
    INSERT INTO Publicaciones (Id_usuario, Id_Categoria, Titulo, Contenido, Imagen, Tipo)
    VALUES (p_Id_usuario, p_Id_Categoria, p_Titulo, p_Contenido, p_Imagen, p_Tipo);
END $$

DELIMITER ;


-- CREAR PUBLICACION
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

CREATE TABLE Donadores (
    Id_Donador INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario INT NOT NULL,
    Id_Donacion INT NOT NULL,
    Cantidad DECIMAL(10,2) NOT NULL,
    Fecha_Donacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Comentario TEXT,
    FOREIGN KEY (Id_usuario) REFERENCES Usuarios(Id_usuario),
    FOREIGN KEY (Id_Donacion) REFERENCES Donaciones(Id_Donacion)
);


-- DROPS
DROP PROCEDURE VerificarCorreo;
DROP PROCEDURE sp_registrar_usuario;
DROP PROCEDURE  LoginUsuario;