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

