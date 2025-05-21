-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS PostArt;
USE PostArt;

DROP DATABASE PostArt;

SELECT * FROM Usuario;
-- Tabla de Usuarios
CREATE TABLE Usuario (
    ID_Usuario INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(50) NOT NULL,
    ApePa VARCHAR(50) NOT NULL,
    ApeMa VARCHAR(50) NOT NULL,
    Nickname VARCHAR(50) NOT NULL,
    Correo VARCHAR(100) NOT NULL UNIQUE,
    Contrasena VARCHAR(255) NOT NULL,
    Foto_perfil MEDIUMBLOB NULL,
    Biografia TEXT NULL,
    Rol VARCHAR(20) NOT NULL COMMENT 'Indica si es Artista o Visitante',
    Estado VARCHAR(20) DEFAULT 'Activo',
    Fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

INSERT INTO Usuario (Nombre, ApePa, ApeMa, Nickname, Correo, Contrasena, Rol)
VALUES (
    'Ana',
    'García',
    'Sánchez',
    'AnaArt',
    'ana.garcia@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- Hash de "Password29$"
    'Artista'
);

CREATE TABLE Categorias(
Id_Categoria INT AUTO_INCREMENT PRIMARY KEY,
Nombre VARCHAR(50) NOT NULL
);

-- Tabla de Publicaciones
CREATE TABLE Publicaciones (
    Id_publicacion INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario INT NOT NULL,
    Id_Categoria INT NOT NULL,
    Titulo VARCHAR(100) NOT NULL,
    Contenido TEXT NULL,
    Imagen MEDIUMBLOB NOT NULL,
    Tipo VARCHAR(20) NOT NULL COMMENT 'Indica si es Público o de Subscripción',
	Fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	Estado VARCHAR(20) DEFAULT 'Activo',
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario),
	FOREIGN KEY (Id_Categoria) REFERENCES Categorias(Id_Categoria)
);

-- Tabla de Comentarios
CREATE TABLE Comentarios (
    Id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    Id_publicacion INT NOT NULL,
    Id_usuario INT NOT NULL,
    Comentario TEXT NOT NULL,
    Fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Estado VARCHAR(20) DEFAULT 'Activo',
    FOREIGN KEY (Id_publicacion) REFERENCES Publicaciones(Id_publicacion),
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario)
);

-- Tabla de Seguidores
CREATE TABLE Seguidores (
    Id_seguidor INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario_seguidor INT NOT NULL,
    Id_usuario_artista INT NOT NULL,
    Fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Estado VARCHAR(20) DEFAULT 'Activo',
    FOREIGN KEY (Id_usuario_seguidor) REFERENCES Usuario(Id_usuario),
    FOREIGN KEY (Id_usuario_artista) REFERENCES Usuario(Id_usuario)
);

-- Tabla de Donaciones
CREATE TABLE Donadores (
    Id_donadores INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario_donante INT NOT NULL,
    Id_usuario_artista INT NOT NULL,
    Monto DECIMAL(10,2) NOT NULL,
    Fecha_donacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (Id_usuario_donante) REFERENCES Usuario(Id_usuario),
    FOREIGN KEY (Id_usuario_artista) REFERENCES Usuario(Id_usuario)
);

CREATE TABLE Donaciones(
	Id_Donacion INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario INT NOT NULL,
    Id_Categoria INT NOT NULL,
    Titulo VARCHAR(100) NOT NULL,
    Contenido TEXT NOT NULL,
    Imagen MEDIUMBLOB NULL,
    Video_url VARCHAR(255) NULL,
    Meta DECIMAL(10,2) NOT NULL,
    Fecha_Limite TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario),
	FOREIGN KEY (Id_Categoria) REFERENCES Categorias(Id_Categoria)
);

-- Tabla de Subscripciones
CREATE TABLE Subscripciones (
    Id_subscripcion INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario_comprador INT NOT NULL,
    Id_usuario_artista INT NOT NULL,
    Monto DECIMAL(10,2) NOT NULL,
    Fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Fecha_fin DATE NULL,
    Estado VARCHAR(20) DEFAULT 'Activa' NOT NULL,
    FOREIGN KEY (Id_usuario_comprador) REFERENCES Usuario(Id_usuario),
    FOREIGN KEY (Id_usuario_artista) REFERENCES Usuario(Id_usuario)
);

-- Tabla de Mensajes Directos
CREATE TABLE Mensajes (
    Id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    Id_emisor INT NOT NULL,
    Id_receptor INT NOT NULL,
    Contenido TEXT NOT NULL,
    Fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Leido BIT DEFAULT FALSE NULL,
    FOREIGN KEY (Id_emisor) REFERENCES Usuario(Id_usuario),
    FOREIGN KEY (Id_receptor) REFERENCES Usuario(Id_usuario)
);

-- Tabla de Chats Grupales
CREATE TABLE Chats_Grupales (
    Id_chat INT AUTO_INCREMENT PRIMARY KEY,
    Id_usuario_artista INT NOT NULL,
    Nombre_chat VARCHAR(100) NOT NULL,
    Fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (Id_usuario_artista) REFERENCES Usuario(Id_usuario)
);

CREATE TABLE Miembros_Chat (
  Id_Miembro INT AUTO_INCREMENT PRIMARY KEY,
  Id_usuario INT NOT NULL,
  Id_chat INT NOT NULL,
  FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario),
  FOREIGN KEY (Id_chat) REFERENCES Chats_Grupales(Id_chat)
);

-- Tabla de Mensajes Grupales
CREATE TABLE Mensajes_Grupales (
    Id_mensaje_grupal INT AUTO_INCREMENT PRIMARY KEY,
    Id_chat INT NOT NULL,
    Id_usuario INT NOT NULL,
    Contenido TEXT NOT NULL,
    Fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (Id_chat) REFERENCES Chats_Grupales(Id_chat),
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario)
);


CREATE TABLE Me_Gusta(
Id_Like INT AUTO_INCREMENT PRIMARY KEY,
Id_usuario INT NOT NULL,
Id_publicacion INT NOT NULL,
FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario),
FOREIGN KEY (Id_publicacion) REFERENCES Publicaciones(Id_publicacion)
);

CREATE TABLE Redes_sociales(
Id_red INT AUTO_INCREMENT PRIMARY KEY,
Id_usuario INT NOT NULL,
Nombre  VARCHAR (50) NOT NULL,
Link VARCHAR (255) NOT NULL,
FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario)
);


