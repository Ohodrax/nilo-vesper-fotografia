-- Opcional: registra apenas imagens existentes e evita duplicação ao repetir.
INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_capa_principal_e139a4_d38b9ab9f56341f889953bc6e6fc0c83~mv2.jpg', 'pictures', 'capa_principal', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_capa_principal_e139a4_d38b9ab9f56341f889953bc6e6fc0c83~mv2.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_bg-image-2.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_bg-image-2.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_bg-image-3.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_bg-image-3.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_bg-image-4.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_bg-image-4.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_bg-image-5.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_bg-image-5.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_carrossel-home-1.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_carrossel-home-1.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_carrossel-home-2.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_carrossel-home-2.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_carrossel-home-3.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_carrossel-home-3.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_carrossel-home-4.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_carrossel-home-4.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_content-about-image-1.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_content-about-image-1.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_content-image-1.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_content-image-1.jpg');

INSERT INTO tb_image (nome, referencia, localizacao, status, criado_por, alterado_por)
SELECT 'pictures_gallery_content-image-2.jpg', 'pictures', 'gallery', 1, 'SETUP', 'SETUP'
WHERE NOT EXISTS (SELECT 1 FROM tb_image WHERE referencia = 'pictures' AND nome = 'pictures_gallery_content-image-2.jpg');
