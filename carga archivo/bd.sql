-- Tabla de Publicaciones
CREATE TABLE Publicaciones (
    Id_publicacion INT AUTO_INCREMENT PRIMARY KEY,
    Titulo VARCHAR(100) NOT NULL COMMENT 'Título visible de la publicación',
    Contenido TEXT NULL COMMENT 'Texto descriptivo de la publicación',
    Imagen MEDIUMBLOB NOT NULL COMMENT 'Imagen asociada a la publicación',
    Tipo VARCHAR(20) NOT NULL COMMENT 'Indica si es Pública o por Subscripción',
    Fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT 'Fecha en que fue creada la publicación',
    Estado VARCHAR(20) DEFAULT 'Activo' COMMENT 'Estado actual de la publicación'
);

use PostArt;
select * from Publicaciones;