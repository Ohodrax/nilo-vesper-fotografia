<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_post();
rate_limit('recovery', 5, 900);
$email = post_text('emailsendrecovery');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_result(false, 'E-mail inválido.', 400);
$db = new db_crud();
$db->table = 'tb_usuario';
$db->filters = [['email', '=', $email], ['status', '=', 1]];
$user = $db->default_select_data()[0] ?? null;
if ($user) {
    $token = bin2hex(random_bytes(32));
    $pdo = (new mySqlConnection())->newConnection();
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('UPDATE tb_recovery SET status = 0 WHERE id_usuario = ?');
        $stmt->execute([$user['id_usuario']]);
        $stmt = $pdo->prepare('INSERT INTO tb_recovery (id_usuario, hash, expire_time, criado_por, alterado_por) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$user['id_usuario'], hash('sha256', $token), date('Y-m-d H:i:s', time() + 900), 'RECOVERY', 'RECOVERY']);
        $pdo->commit();
    } catch (Throwable $error) { $pdo->rollBack(); throw new RuntimeException('Recuperação indisponível.'); }
    $link = ROOT_URL . '/recovery/' . $token;
    if (!send_email('Olá ' . h($user['nome']) . ',<br>Use este link em até 15 minutos:<br><a href="' . h($link) . '">Alterar senha</a>', 'Recuperação de senha', $user['email'])) {
        $stmt = $pdo->prepare('UPDATE tb_recovery SET status = 0 WHERE hash = ?');
        $stmt->execute([hash('sha256', $token)]);
    }
}
json_result(true, 'Solicitação recebida. Se houver uma conta ativa e o envio estiver disponível, você receberá as instruções.');
