-- VIEWS
USE PostArt;

CREATE VIEW Vista_Publicaciones_Activas AS
SELECT 
  p.Id_publicacion,
  p.Titulo,
  p.Tipo,
  p.Fecha_creacion,
  u.Nickname AS Autor,
  c.Nombre AS Categoria,
  (SELECT COUNT(*) FROM Me_Gusta WHERE Id_publicacion = p.Id_publicacion) AS Total_Likes
FROM Publicaciones p
JOIN Usuario u ON p.Id_usuario = u.ID_Usuario
JOIN Categorias c ON p.Id_Categoria = c.Id_Categoria
WHERE p.Estado = 'Activo';



CREATE VIEW Vista_Comentarios_Publicaciones AS
SELECT 
  c.Id_comentario,
  c.Id_publicacion,
  p.Titulo AS Publicacion,
  u.Nickname AS Autor_Comentario,
  c.Comentario,
  c.Fecha_comentario,
  c.Estado
FROM Comentarios c
JOIN Publicaciones p ON c.Id_publicacion = p.Id_publicacion
JOIN Usuario u ON c.Id_usuario = u.ID_Usuario
WHERE c.Estado = 'Activo';


CREATE VIEW Vista_Donaciones_Recaudadas AS
SELECT 
  d.Id_Donacion,
  d.Titulo,
  u.Nickname AS Artista,
  d.Meta,
  IFNULL(SUM(do.Monto), 0) AS Recaudado,
  ROUND(IFNULL(SUM(do.Monto) / d.Meta * 100, 0), 2) AS Porcentaje_Recaudado
FROM Donaciones d
LEFT JOIN Donadores do ON d.Id_usuario = do.Id_usuario_artista
JOIN Usuario u ON d.Id_usuario = u.ID_Usuario
GROUP BY d.Id_Donacion;


CREATE VIEW Vista_Usuarios_Con_Seguidores AS
SELECT 
  u.ID_Usuario,
  u.Nickname,
  COUNT(s.Id_usuario_seguidor) AS Total_Seguidores
FROM Usuario u
LEFT JOIN Seguidores s ON u.ID_Usuario = s.Id_usuario_artista AND s.Estado = 'Activo'
WHERE u.Rol = 'Artista'
GROUP BY u.ID_Usuario;


CREATE VIEW Vista_Subscripciones_Activas AS
SELECT 
  s.Id_subscripcion,
  c.Nickname AS Comprador,
  a.Nickname AS Artista,
  s.Monto,
  s.Fecha_inicio,
  s.Fecha_fin
FROM Subscripciones s
JOIN Usuario c ON s.Id_usuario_comprador = c.ID_Usuario
JOIN Usuario a ON s.Id_usuario_artista = a.ID_Usuario
WHERE s.Estado = 'Activa';


CREATE VIEW Vista_Mensajes_Personales_No_Leidos AS
SELECT 
  m.Id_mensaje,
  em.Nickname AS Emisor,
  re.Nickname AS Receptor,
  m.Contenido,
  m.Fecha_envio
FROM Mensajes m
JOIN Usuario em ON m.Id_emisor = em.ID_Usuario
JOIN Usuario re ON m.Id_receptor = re.ID_Usuario
WHERE m.Leido = 0;


CREATE VIEW Vista_Interaccion_Publicacion AS
SELECT 
  p.Id_publicacion,
  p.Titulo,
  u.Nickname AS Autor,
  (SELECT COUNT(*) FROM Me_Gusta mg WHERE mg.Id_publicacion = p.Id_publicacion) AS Likes,
  (SELECT COUNT(*) FROM Comentarios c WHERE c.Id_publicacion = p.Id_publicacion AND c.Estado = 'Activo') AS Comentarios
FROM Publicaciones p
JOIN Usuario u ON p.Id_usuario = u.ID_Usuario;


CREATE VIEW Vista_Miembros_Chat_Grupal AS
SELECT 
  cg.Nombre_chat,
  u.Nickname AS Miembro,
  mc.Id_chat
FROM Miembros_Chat mc
JOIN Usuario u ON mc.Id_usuario = u.ID_Usuario
JOIN Chats_Grupales cg ON mc.Id_chat = cg.Id_chat;
