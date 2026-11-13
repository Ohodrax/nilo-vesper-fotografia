<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_post();
rate_limit('contact', 5, 900);
$name = post_text('form_inputNome', 150);
$email = post_text('form_inputEmail', 150);
$phone = post_text('form_inputTelefone', 30);
$message = post_text('form_inputMensagem', 10000);
if (!$name || !$message || !filter_var($email, FILTER_VALIDATE_EMAIL)) json_result(false, 'Preencha nome, e-mail válido e mensagem.', 400);
$db = new db_crud();
$db->table = 'tb_lead';
$db->data = ['nome' => $name, 'email' => $email, 'telefone' => $phone, 'mensagem' => $message, 'criado_por' => 'SITE', 'alterado_por' => 'SITE'];
$db->default_insert_data();
$db->table = 'tb_parametro';
$db->filters = [['input_reference', '=', 'input_email']];
$destination = $db->default_select_data()[0]['input_value'] ?? '';
$sent = filter_var($destination, FILTER_VALIDATE_EMAIL) && send_email('Novo contato:<br>Nome: ' . h($name) . '<br>E-mail: ' . h($email) . '<br>Telefone: ' . h($phone) . '<br>Mensagem: ' . nl2br(h($message)), 'Novo contato', $destination);
json_result(true, $sent ? 'Contato recebido com sucesso!' : 'Contato registrado. A notificação por e-mail está indisponível no momento.');
