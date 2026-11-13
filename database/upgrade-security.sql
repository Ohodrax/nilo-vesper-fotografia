-- Aplicar uma vez se você já importou um dos scripts antigos. Faça backup antes.
-- Mantém usuários, textos e imagens; invalida os links de recuperação antigos.
ALTER TABLE tb_recovery MODIFY hash CHAR(64) NOT NULL;
UPDATE tb_recovery SET status = 0;
