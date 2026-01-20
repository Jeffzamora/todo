-- Migracion: ampliar enum prioridad de shopping a 4 niveles
-- Antes: ENUM('must','optional')
-- Despues: ENUM('none','low','medium','high')

ALTER TABLE shopping_items
  MODIFY prioridad ENUM('none','low','medium','high') NOT NULL DEFAULT 'none';

UPDATE shopping_items
SET prioridad = CASE prioridad
  WHEN 'must' THEN 'high'
  WHEN 'optional' THEN 'low'
  ELSE 'none'
END;

ALTER TABLE shopping_template_items
  MODIFY prioridad ENUM('none','low','medium','high') NOT NULL DEFAULT 'none';

UPDATE shopping_template_items
SET prioridad = CASE prioridad
  WHEN 'must' THEN 'high'
  WHEN 'optional' THEN 'low'
  ELSE 'none'
END;
