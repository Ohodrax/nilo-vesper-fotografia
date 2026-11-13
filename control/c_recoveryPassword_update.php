<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_post();
rate_limit('reset', 10, 900);
$token = post_text('formRaid', 64);
$password = $_POST['formNewPassword'] ?? '';
if (!preg_match('/\A[a-f0-9]{64}\z/D', $token) || strlen($password) < 12 || strlen($password) > 72 || $password !== ($_POST['formConfirmPassword'] ?? null)) json_result(false, 'Use uma senha de 12 a 72 bytes e confirme os mesmos caracteres.', 400);
$pdo = (new mySqlConnection())->newConnection();
$updated = reset_password($pdo, $token, $password);
if (!$updated) json_result(false, 'Link inválido, expirado ou já utilizado.', 400);
$_SESSION = [];
session_regenerate_id(true);
$_SESSION['showMessage'] = ['return' => true, 'ref' => 'Senha atualizada. Entre novamente.'];
json_result(true, 'Senha atualizada!');
