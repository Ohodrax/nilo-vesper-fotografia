<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_post();
rate_limit('login', 15, 900);
$username = post_text('username');
$password = $_POST['userpassword'] ?? '';
if (strlen($password) > 1024) json_result(false, 'Dados inválidos.', 400);
$db = new db_crud();
$db->table = 'v_select_user_profile';
$db->filters = [['any' => [['nome', '=', $username], ['email', '=', $username]]], ['status', '=', 1], ['status_perfil', '=', 1]];
$db->orderby = 'id_usuario desc';
$user = $db->default_select_data()[0] ?? null;
// Run a password hash check even when no account matches.
$hash = $user['senha'] ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';
if (!$username || !$password || !password_verify($password, $hash) || !$user) json_result(false, 'Usuário ou senha inválidos.');
session_regenerate_id(true);
$_SESSION = [
    'userId' => (int) $user['id_usuario'],
    'userName' => $user['nome'],
    'firstPage' => (int) $user['id_perfil'] === 1 ? 'a_renderAdmin_users.php' : 'a_renderAdmin_home.php',
    'authVersion' => hash('sha256', $user['senha']),
    'lastActivity' => time(),
    'csrf' => bin2hex(random_bytes(32)),
    'showMessage' => ['return' => true, 'ref' => 'Seja bem-vindo(a) ' . $user['nome']],
];
json_result(true, 'Aguarde, por favor...');
