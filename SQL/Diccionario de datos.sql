-- DICCIONARIO DE DATOS POSTART

USE PostArt;


SELECT 
    t.table_name AS 'Tabla',
    t.table_comment AS 'Descripción_tabla',
    c.column_name AS 'Columna',
    c.column_comment AS 'Descripción_columna',
    c.column_type AS 'Tipo_dato',
    CONCAT(
        CASE 
            WHEN c.character_maximum_length IS NOT NULL THEN CONCAT('Longitud: ', c.character_maximum_length)
            WHEN c.numeric_precision IS NOT NULL THEN CONCAT('Precisión: ', c.numeric_precision, ', Escala: ', COALESCE(c.numeric_scale, 0))
            ELSE ''
        END,
        CASE 
            WHEN c.column_key = 'PRI' THEN ', PK'
            WHEN c.column_key = 'UNI' THEN ', Unique'
            ELSE ''
        END,
        CASE 
            WHEN c.extra = 'auto_increment' THEN ', Auto-increment'
            ELSE ''
        END
    ) AS 'Dominio',
    CONCAT(
        IF(c.is_nullable = 'NO', 'NOT NULL, ', ''),
        IF(c.column_default IS NOT NULL, CONCAT('Default: ', c.column_default, ', '), ''),
        IF(kcu.referenced_table_name IS NOT NULL, 
           CONCAT('FK → ', kcu.referenced_table_name, '(', kcu.referenced_column_name, '), '), ''),
        IF(c.column_type LIKE 'enum(%', CONCAT('Valores permitidos: ', c.column_type), '')
    ) AS 'Restricciones'
FROM 
    information_schema.columns c
JOIN 
    information_schema.tables t ON c.table_name = t.table_name AND c.table_schema = t.table_schema
LEFT JOIN 
    information_schema.key_column_usage kcu ON c.table_schema = kcu.table_schema
    AND c.table_name = kcu.table_name
    AND c.column_name = kcu.column_name
    AND kcu.referenced_table_name IS NOT NULL
WHERE 
    c.table_schema = 'PostArt'
ORDER BY 
    t.table_name, c.ordinal_position;