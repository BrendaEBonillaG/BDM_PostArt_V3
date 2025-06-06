DROP DATABASE PostArt;
-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS PostArt;
USE PostArt;
SELECT * FROM Usuario;
DESCRIBE Usuario;

SELECT * FROM Publicaciones;

SELECT * FROM Seguidores;

-- Tabla de Usuarios
CREATE TABLE Usuario (
    ID_Usuario INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL COMMENT 'Nombre del usuario',
    ApePa VARCHAR(50) NOT NULL COMMENT 'Apellido paterno del usuario',
    ApeMa VARCHAR(50) NOT NULL,
    Nickname VARCHAR(50) NOT NULL COMMENT 'Nombre público del usuario en la plataforma',
    Correo VARCHAR(100) NOT NULL UNIQUE COMMENT 'Correo electrónico usado para iniciar sesión',
    Contrasena VARCHAR(255) NOT NULL COMMENT 'Contraseña encriptada del usuario',
    Foto_perfil MEDIUMBLOB NULL COMMENT 'Imagen de perfil del usuario (formato BLOB)',
    Biografia TEXT NULL COMMENT 'Descripción breve del usuario',
    Rol VARCHAR(20) NOT NULL COMMENT 'Indica si es Artista o Visitante',
    Estado VARCHAR(20) DEFAULT 'Activo' COMMENT 'Estado del usuario (Activo, Suspendido, etc.)',
    Fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha en la que el usuario se registró'
);

-- Tabla de Categorías
CREATE TABLE Categorias(
    Id_Categoria INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL COMMENT 'Nombre de la categoría para clasificar publicaciones'
);

-- Tabla de Publicaciones
CREATE TABLE Publicaciones (
    Id_publicacion INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario INT NOT NULL COMMENT 'Usuario que creó la publicación',
    Id_Categoria INT NOT NULL COMMENT 'Categoría asignada a la publicación',
    Titulo VARCHAR(100) NOT NULL COMMENT 'Título visible de la publicación',
    Contenido TEXT NULL COMMENT 'Texto descriptivo de la publicación',
    Imagen MEDIUMBLOB NOT NULL COMMENT 'Imagen asociada a la publicación',
    Tipo VARCHAR(20) NOT NULL COMMENT 'Indica si es Pública o por Subscripción',
    Fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha en que fue creada la publicación',
    Estado VARCHAR(20) DEFAULT 'Activo' COMMENT 'Estado actual de la publicación',
	FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario),
	FOREIGN KEY (Id_Categoria) REFERENCES Categorias(Id_Categoria)
);

-- Tabla de Comentarios
CREATE TABLE Comentarios (
    Id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    Id_publicacion INT NOT NULL COMMENT 'Publicación a la que pertenece el comentario',
    Id_usuario INT NOT NULL COMMENT 'Usuario que escribió el comentario',
    Comentario TEXT NOT NULL COMMENT 'Contenido del comentario',
    Fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha del comentario',
    Estado VARCHAR(20) DEFAULT 'Activo' COMMENT 'Estado del comentario (Activo/Inactivo)',
    FOREIGN KEY (Id_publicacion) REFERENCES Publicaciones(Id_publicacion),
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario)
);

-- Tabla de Seguidores
CREATE TABLE Seguidores (
    Id_seguidor INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario_seguidor INT NOT NULL COMMENT 'Usuario que sigue a otro',
    Id_usuario_artista INT NOT NULL COMMENT 'Artista que es seguido',
    Fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha en que comenzó el seguimiento',
    Estado VARCHAR(20) DEFAULT 'Activo' COMMENT 'Estado del seguimiento (Activo/Cancelado)',
	FOREIGN KEY (Id_usuario_seguidor) REFERENCES Usuario(Id_usuario),
    FOREIGN KEY (Id_usuario_artista) REFERENCES Usuario(Id_usuario)
);

-- Tabla de Donadores (usuarios que donan)
CREATE TABLE Donadores (
    Id_donadores INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario_donante INT NOT NULL COMMENT 'Usuario que realizó la donación',
    Id_usuario_artista INT NOT NULL COMMENT 'Artista que recibió la donación',
    Id_donacion  INT NOT NULL COMMENT 'ID de la donación relacionada',
    Monto DECIMAL(10,2) NOT NULL COMMENT 'Monto donado',
    Fecha_donacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha en que se realizó la donación',
	FOREIGN KEY (Id_usuario_donante) REFERENCES Usuario(Id_usuario),
    FOREIGN KEY (Id_usuario_artista) REFERENCES Usuario(Id_usuario),
	FOREIGN KEY (Id_donacion) REFERENCES Donaciones(Id_donacion)
);

ALTER TABLE Donadores
ADD COLUMN Id_donacion INT NOT NULL COMMENT 'ID de la donación relacionada',
ADD FOREIGN KEY (Id_donacion) REFERENCES Donaciones(Id_donacion);

-- Tabla de Donaciones (campañas de donación)
CREATE TABLE Donaciones(
    Id_Donacion INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario INT NOT NULL COMMENT 'Usuario que creó la campaña',
    Id_Categoria INT NOT NULL COMMENT 'Categoría de la campaña',
    Titulo VARCHAR(100) NOT NULL COMMENT 'Nombre o título de la campaña',
    Contenido TEXT NOT NULL COMMENT 'Descripción de la campaña de donación',
    Imagen MEDIUMBLOB NULL COMMENT 'Imagen promocional de la campaña',
    Video_url VARCHAR(255) NULL COMMENT 'Enlace a video explicativo (opcional)',
    Meta DECIMAL(10,2) NOT NULL COMMENT 'Monto objetivo de la campaña',
    Fecha_Limite TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha límite para recibir donaciones',
    Fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha de publicación de la campaña',
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario),
	FOREIGN KEY (Id_Categoria) REFERENCES Categorias(Id_Categoria)
);

-- Tabla de Subscripciones
CREATE TABLE Subscripciones (
    Id_subscripcion INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario_comprador INT NOT NULL COMMENT 'Usuario que paga la subscripción',
    Id_usuario_artista INT NOT NULL COMMENT 'Artista que recibe la subscripción',
    Monto DECIMAL(10,2) NOT NULL COMMENT 'Monto pagado por la subscripción',
    Fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha de inicio de la subscripción',
    Fecha_fin DATE NULL COMMENT 'Fecha de finalización (si aplica)',
    Estado VARCHAR(20) DEFAULT 'Activa' NOT NULL COMMENT 'Estado actual de la subscripción',
	FOREIGN KEY (Id_usuario_comprador) REFERENCES Usuario(Id_usuario),
    FOREIGN KEY (Id_usuario_artista) REFERENCES Usuario(Id_usuario)
);



SELECT * FROM Mensajes_Privado;
-- Tabla de Mensajes Directos
CREATE TABLE Chat_Privado (
    id_chat INT AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_remitente INT NOT NULL,
    id_emisor INT NOT NULL,
    CONSTRAINT chk_diferentes CHECK (id_remitente <> id_emisor), -- Evita que alguien cree un chat consigo mismo
    FOREIGN KEY (id_remitente) REFERENCES Usuario(Id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_emisor) REFERENCES Usuario(Id_usuario) ON DELETE CASCADE
);

CREATE TABLE Mensajes_Privado (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_chat_Privado INT NOT NULL,
    id_usuario INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tipo VARCHAR(500) DEFAULT 'texto',
    visto BIT NOT NULL DEFAULT 0,
    FOREIGN KEY (id_chat_Privado) REFERENCES Chat_Privado(id_chat) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(Id_usuario) ON DELETE CASCADE
);


CREATE TABLE Chat_Grupal (
    id_chat INT AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nombre VARCHAR(50) NOT NULL,
    numero_Participantes INT NOT NULL,
    imagen LONGBLOB
);

CREATE TABLE Participantes_Grupal (
    id_participante INT AUTO_INCREMENT PRIMARY KEY,
    id_ChatGrupal INT NOT NULL,
    id_usuario INT NOT NULL,
    FOREIGN KEY (id_ChatGrupal) REFERENCES Chat_Grupal(id_chat) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(Id_usuario) ON DELETE CASCADE
);
DROP TABLE Mensajes_Grupales;
CREATE TABLE Mensajes_Grupales (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_chat_Grupal INT NOT NULL,
    id_usuario INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tipo VARCHAR(500) DEFAULT 'texto',
    visto BIT NOT NULL DEFAULT 0,
    FOREIGN KEY (id_chat_Grupal) REFERENCES Chat_Grupal(id_chat) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(Id_usuario) ON DELETE CASCADE
);
DESCRIBE Mensajes_Grupales;

-- Tabla de Me Gusta
CREATE TABLE Me_Gusta(
    Id_Like INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario INT NOT NULL COMMENT 'Usuario que dio "me gusta"',
    Id_publicacion INT NOT NULL COMMENT 'Publicación que recibió el "me gusta"',
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario),
	FOREIGN KEY (Id_publicacion) REFERENCES Publicaciones(Id_publicacion)

);

-- Tabla de Redes Sociales
CREATE TABLE Redes_sociales(
    Id_red INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario INT NOT NULL COMMENT 'Usuario que enlazó la red social',
    Nombre  VARCHAR(50) NOT NULL COMMENT 'Nombre de la red (Instagram, Twitter, etc.)',
    Link VARCHAR(255) NOT NULL COMMENT 'Enlace al perfil de red social del usuario',
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario)
);


SELECT * FROM DONACIONES;
SELECT * FROM USUARIO;
SELECT * FROM PUBLICACIONES;

