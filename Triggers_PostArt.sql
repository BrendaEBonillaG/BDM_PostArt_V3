-- TRIGGERS
USE PostArt;

CREATE TRIGGER suspender_publicaciones
AFTER UPDATE ON Usuario
FOR EACH ROW
BEGIN
  IF NEW.Estado = 'Suspendido' THEN
    UPDATE Publicaciones SET Estado = 'Inactivo' WHERE Id_usuario = NEW.ID_Usuario;
    UPDATE Comentarios SET Estado = 'Inactivo' WHERE Id_usuario = NEW.ID_Usuario;
  END IF;
END;

CREATE TRIGGER eliminar_seguidores
AFTER DELETE ON Usuario
FOR EACH ROW
BEGIN
  DELETE FROM Seguidores WHERE Id_usuario_seguidor = OLD.ID_Usuario OR Id_usuario_artista = OLD.ID_Usuario;
END;
