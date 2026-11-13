<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
$token = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if (!preg_match('/\A[a-f0-9]{64}\z/D', $token)) { header('Location: /login'); exit; }
$db = new db_crud();
$db->table = 'tb_recovery';
$db->filters = [['hash', '=', hash('sha256', $token)], ['status', '=', 1], ['expire_time', '>', date('Y-m-d H:i:s')]];
if (!$db->default_select_data()) {
    $_SESSION['showMessage'] = ['return' => false, 'ref' => 'Link inválido ou expirado.'];
    header('Location: /login'); exit;
}
$raid = $token;
