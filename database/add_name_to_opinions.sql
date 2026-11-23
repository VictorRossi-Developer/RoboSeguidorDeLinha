-- Execute uma única vez se a tabela opinions já existir no banco.
USE feira_iot;

ALTER TABLE opinions
    ADD COLUMN name VARCHAR(120) NOT NULL DEFAULT '' AFTER id;

-- Opcional: após conferir/preencher os nomes dos registros antigos,
-- remova o DEFAULT para exigir nome em todos os novos registros.
ALTER TABLE opinions
    ALTER COLUMN name DROP DEFAULT;
