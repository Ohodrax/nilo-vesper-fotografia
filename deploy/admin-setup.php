<?php
// Local CLI only. Never deployed. Generates a random password; does not connect to DB.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
fwrite(STDOUT, "E-mail do administrador: ");
$email = trim(fgets(STDIN));
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150 || str_contains($email, "'")) { fwrite(STDERR, "E-mail inválido.\n"); exit(1); }
$password = bin2hex(random_bytes(18));
$hash = password_hash($password, PASSWORD_DEFAULT);
$directory = __DIR__ . '/../.deploy-artifacts';
if (!is_dir($directory)) mkdir($directory, 0700);
$sql = "-- Arquivo privado; importe no phpMyAdmin e remova após usar.\n";
$sql .= "INSERT INTO tb_usuario (id_usuario, id_perfil, nome, email, senha, status, criado_por, alterado_por) VALUES (1, 1, 'Administrador', '$email', '$hash', 1, 'SETUP', 'SETUP') ON DUPLICATE KEY UPDATE email = VALUES(email), senha = VALUES(senha), status = 1;\n";
file_put_contents($directory . '/admin-setup.sql', $sql);
fwrite(STDOUT, "Senha gerada: $password\nGuarde no gerenciador de senhas. Importe .deploy-artifacts/admin-setup.sql e apague o arquivo. Nunca faça commit dele.\n");
