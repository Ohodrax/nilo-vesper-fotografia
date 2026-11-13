-- Faça backup do banco antes de executar. Repetível; não altera usuários ou credenciais.
START TRANSACTION;

UPDATE tb_image SET status = 0, destaque_carrossel = 0
WHERE nome IN ('paulo-barete.png', 'about_paulo-barete.png', 'pictures_gallery_paulo-barete.png', 'parceiros.png');

UPDATE tb_parametro SET input_value = CASE input_reference
    WHEN 'textarea_capa' THEN 'Um olhar sobre luz, expressão e movimento.'
    WHEN 'textarea_about_capa' THEN 'Nilo Vesper Fotografia é uma identidade fictícia criada para este projeto demonstrativo da Spartun Digital. O site explora retratos, moda e composição visual, sem representar a trajetória de uma pessoa real.'
    WHEN 'textarea_clients_capa' THEN 'Uma seleção visual para explorar diferentes possibilidades de fotografia. Este projeto demonstrativo não representa vínculos comerciais com as marcas retratadas.'
    ELSE '' END
WHERE input_value REGEXP '([Pp][Aa][Uu][Ll][Oo][ _-]*[Bb][Aa][Rr][Ee]([Tt][Aa]|[Tt][Ee])|[Bb][Aa][Rr][Ee][Tt][Aa]|30[ ]+[Aa][Nn][Oo][Ss])';

COMMIT;
