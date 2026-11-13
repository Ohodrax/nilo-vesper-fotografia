<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_post();
require_admin(true);
$action = post_text('formAction', 10);
$id = filter_var($_POST['targetId'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 2]]);
if (!in_array($action, ['new', 'edit', 'exclude'], true) || ($action !== 'new' && !$id)) json_result(false, 'Usuário ou ação inválidos.', 400);
$db = new db_crud();
$db->table = 'tb_usuario';
if ($action === 'exclude') {
    $db->id = $id;
    if ((int) $_SESSION['userId'] === $id) json_result(false, 'Não é possível excluir sua própria conta.', 400);
    json_result($db->default_delete_data(), 'Operação concluída.');
}
$name = post_text('formName', 100);
$email = post_text('formEmail', 150);
$password = $_POST['formPassword'] ?? '';
$profile = filter_var($_POST['formPerfil'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (strlen($name) < 4 || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$profile || (($action === 'new' || $password !== '') && (strlen($password) < 12 || strlen($password) > 72))) json_result(false, 'Confira nome, e-mail, perfil e senha (12 a 72 bytes).', 400);
$profiles = new db_crud();
$profiles->table = 'tb_perfil';
$profiles->filters = [['id_perfil', '=', $profile], ['status', '=', 1]];
if (!$profiles->default_select_data()) json_result(false, 'Perfil inválido.', 400);
$db->data = ['nome' => $name, 'email' => $email, 'id_perfil' => $profile, 'alterado_por' => $_SESSION['userName']];
if ($password !== '') $db->data['senha'] = password_hash($password, PASSWORD_DEFAULT);
if ($action === 'new') {
    $db->data['criado_por'] = $_SESSION['userName'];
    $ok = $db->default_insert_data();
} else {
    $db->id = $id;
    $ok = $db->default_update_data();
}
json_result($ok, $ok ? 'Usuário salvo.' : 'Não foi possível salvar.');
