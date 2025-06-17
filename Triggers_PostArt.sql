-- TRIGGERS
USE PostArt;


DELIMITER $$

CREATE TRIGGER suspender_publicaciones
AFTER UPDATE ON Usuario
FOR EACH ROW
BEGIN
  IF NEW.Estado = 'Suspendido' THEN
    UPDATE Publicaciones 
    SET Estado = 'Inactivo' 
    WHERE Id_usuario = NEW.ID_Usuario;

    UPDATE Comentarios 
    SET Estado = 'Inactivo' 
    WHERE Id_usuario = NEW.ID_Usuario;
  END IF;
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER desactivar_seguidores
AFTER UPDATE ON Usuario
FOR EACH ROW
BEGIN
  IF NEW.Estado = 'Inactivo' OR NEW.Estado = 'Suspendido' THEN
    UPDATE Seguidores 
    SET Estado = 'Inactivo'
    WHERE Id_usuario_seguidor = NEW.ID_Usuario 
       OR Id_usuario_artista = NEW.ID_Usuario;
  END IF;
END$$

DELIMITER ;

DELIMITER //

CREATE TRIGGER agregar_a_chat_grupal_al_subscribir
AFTER INSERT ON Subscripciones
FOR EACH ROW
BEGIN
    DECLARE chat_id INT;
    DECLARE nombre_artista VARCHAR(50);

    -- Primero buscamos si ya existe un chat grupal para ese artista
    SELECT id_chat INTO chat_id 
    FROM Chat_Grupal 
    WHERE nombre = (
        SELECT Nickname FROM Usuario WHERE ID_Usuario = NEW.Id_usuario_artista
    )
    LIMIT 1;

    -- Si no existe, lo creamos
    IF chat_id IS NULL THEN
        -- Obtenemos el nombre del artista
        SELECT Nickname INTO nombre_artista FROM Usuario WHERE ID_Usuario = NEW.Id_usuario_artista;

        INSERT INTO Chat_Grupal (nombre, numero_Participantes)
        VALUES (nombre_artista, 1);

        SET chat_id = LAST_INSERT_ID();
    ELSE
        -- Si el chat ya existe, aumentamos el contador de participantes
        UPDATE Chat_Grupal SET numero_Participantes = numero_Participantes + 1 WHERE id_chat = chat_id;
    END IF;

    -- Agregamos al nuevo suscriptor como participante
    INSERT INTO Participantes_Grupal (id_ChatGrupal, id_usuario)
    VALUES (chat_id, NEW.Id_usuario_comprador);
END;
//

DELIMITER ;


