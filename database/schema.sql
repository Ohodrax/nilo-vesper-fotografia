-- SOMENTE para um banco vazio selecionado no phpMyAdmin.
-- Não apaga nem substitui tabelas. Não cria credenciais ou conta com senha padrão.
-- A aplicação atual não precisa de CREATE PROCEDURE.
SET NAMES utf8mb4;

CREATE TABLE tb_perfil (
    id_perfil              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    nome                   VARCHAR(100)  NOT NULL,
    first_page             VARCHAR(100)  NOT NULL DEFAULT 'a_renderAdmin_home.php',
    status                 TINYINT(1)    NOT NULL DEFAULT 1,
    criado_por             VARCHAR(100)  NULL,
    alterado_por           VARCHAR(100)  NULL,
    data_criacao           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_ultima_alteracao  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_perfil),
    UNIQUE KEY uk_perfil_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_usuario (
    id_usuario             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_perfil              INT UNSIGNED  NOT NULL,
    nome                   VARCHAR(100)  NOT NULL,
    email                  VARCHAR(150)  NOT NULL,
    senha                  VARCHAR(255)  NOT NULL,
    status                 TINYINT(1)    NOT NULL DEFAULT 1,
    criado_por             VARCHAR(100)  NULL,
    alterado_por           VARCHAR(100)  NULL,
    data_criacao           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_ultima_alteracao  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario),
    UNIQUE KEY uk_usuario_email (email),
    KEY idx_usuario_nome (nome),
    KEY idx_usuario_perfil (id_perfil),
    CONSTRAINT fk_usuario_perfil FOREIGN KEY (id_perfil)
        REFERENCES tb_perfil (id_perfil)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_recovery (
    id_recovery            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    id_usuario             INT UNSIGNED  NOT NULL,
    hash                   CHAR(64)      NOT NULL,
    expire_time            DATETIME      NOT NULL,
    status                 TINYINT(1)    NOT NULL DEFAULT 1,
    criado_por             VARCHAR(100)  NULL,
    alterado_por           VARCHAR(100)  NULL,
    data_criacao           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_ultima_alteracao  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_recovery),
    KEY idx_recovery_hash (hash),
    KEY idx_recovery_usuario (id_usuario),
    CONSTRAINT fk_recovery_usuario FOREIGN KEY (id_usuario)
        REFERENCES tb_usuario (id_usuario)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_image (
    id_image               INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    nome                   VARCHAR(255)  NOT NULL,
    referencia             VARCHAR(50)   NOT NULL,
    localizacao            VARCHAR(50)   NOT NULL,
    destaque_carrossel     TINYINT(1)    NOT NULL DEFAULT 0,
    status                 TINYINT(1)    NOT NULL DEFAULT 1,
    criado_por             VARCHAR(100)  NULL,
    alterado_por           VARCHAR(100)  NULL,
    data_criacao           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_ultima_alteracao  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_image),
    KEY idx_image_ref_local (referencia, localizacao),
    KEY idx_image_destaque (localizacao, destaque_carrossel)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_parametro (
    id_parametro           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    page_ref               VARCHAR(50)   NOT NULL,
    input_reference        VARCHAR(100)  NOT NULL,
    input_value            TEXT          NULL,
    status                 TINYINT(1)    NOT NULL DEFAULT 1,
    criado_por             VARCHAR(100)  NULL,
    alterado_por           VARCHAR(100)  NULL,
    data_criacao           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_ultima_alteracao  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_parametro),
    UNIQUE KEY uk_parametro_input_reference (input_reference),
    KEY idx_parametro_page_ref (page_ref)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tb_lead (
    id_lead                INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    nome                   VARCHAR(150)  NOT NULL,
    email                  VARCHAR(150)  NOT NULL,
    telefone               VARCHAR(30)   NULL,
    mensagem               TEXT          NOT NULL,
    status                 TINYINT(1)    NOT NULL DEFAULT 1,
    criado_por             VARCHAR(100)  NULL,
    alterado_por           VARCHAR(100)  NULL,
    data_criacao           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_ultima_alteracao  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_lead),
    KEY idx_lead_email (email),
    KEY idx_lead_data_criacao (data_criacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE VIEW v_select_user_profile AS
SELECT
    u.id_usuario,
    u.id_perfil,
    u.nome,
    u.email,
    u.senha,
    u.status,
    u.criado_por,
    u.alterado_por,
    u.data_criacao,
    u.data_ultima_alteracao,
    p.nome        AS nome_perfil,
    p.first_page  AS first_page,
    p.status      AS status_perfil
FROM tb_usuario u
INNER JOIN tb_perfil p ON p.id_perfil = u.id_perfil;

INSERT INTO tb_perfil (id_perfil, nome, first_page) VALUES
(1, 'Administrador', 'a_renderAdmin_users.php'),
(2, 'Editor', 'a_renderAdmin_home.php');

INSERT INTO tb_parametro (page_ref, input_reference, input_value) VALUES
('home', 'textarea_capa', ''),
('clients', 'textarea_clients_capa', ''),
('about', 'textarea_about_capa', ''),
('contact', 'input_whatsapp_number', ''),
('contact', 'input_whatsapp_number_contact', ''),
('contact', 'input_email', ''),
('contact', 'input_instagram', ''),
('contact', 'input_facebook', ''),
('contact', 'input_linkedin', '');
