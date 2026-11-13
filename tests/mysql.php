<?php
declare(strict_types=1);
// Only a dedicated loopback database whose name starts with test_security_.
$host = getenv('TEST_DB_HOST') ?: '127.0.0.1';
$port = getenv('TEST_DB_PORT') ?: '3306';
$name = getenv('TEST_DB_NAME') ?: '';
if ($host !== '127.0.0.1' || !ctype_digit($port) || !preg_match('/\Atest_security_[a-z0-9_]+\z/D', $name)) throw new RuntimeException('Unsafe test database configuration.');
$pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", getenv('TEST_DB_USER'), getenv('TEST_DB_PASSWORD'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]);
$action = $argv[1] ?? 'prepare';
if ($action === 'cleanup') { $pdo->exec("DROP DATABASE IF EXISTS `$name`"); exit; }
if ($action === 'prepare') {
    $pdo->exec("CREATE DATABASE `$name` CHARACTER SET utf8mb4");
    $pdo->exec("USE `$name`");
    // Verify upgrading the supplied legacy CHAR(32) schema preserves data.
    $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
    $pdo->exec(str_replace('CHAR(64)', 'CHAR(32)', $schema));
    $pdo->exec(file_get_contents(__DIR__ . '/../database/upgrade-security.sql'));
    $seed = file_get_contents(__DIR__ . '/../database/seed-pictures.sql');
    $pdo->exec($seed); $pdo->exec($seed);
    if ((int) $pdo->query('SELECT COUNT(*) FROM tb_image')->fetchColumn() !== 13) throw new RuntimeException('Seed must be repeatable.');
    $stmt = $pdo->prepare('INSERT INTO tb_usuario (id_usuario, id_perfil, nome, email, senha) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([1, 1, '<img src=x onerror=alert(1)>', 'owner@example.invalid', password_hash('test-password-owner-123', PASSWORD_DEFAULT)]);
    $stmt->execute([2, 2, 'Editor', 'editor@example.invalid', password_hash('test-password-editor-123', PASSWORD_DEFAULT)]);
    $stmt = $pdo->prepare('UPDATE tb_parametro SET input_value = ? WHERE input_reference = ?');
    $stmt->execute(['</textarea><script>alert(1)</script>', 'textarea_capa']);
    $stmt->execute(['javascript:alert(1)', 'input_instagram']);
    require_once __DIR__ . '/../bin/password-reset.php';
    $insert = $pdo->prepare('INSERT INTO tb_recovery (id_usuario, hash, expire_time, status) VALUES (2, ?, ?, ?)');
    foreach (['expired' => [-1, 1], 'used' => [900, 0], 'valid' => [900, 1]] as $kind => [$offset, $status]) {
        $token = bin2hex(random_bytes(32));
        $insert->execute([hash('sha256', $token), date('Y-m-d H:i:s', time() + $offset), $status]);
        $ok = reset_password($pdo, $token, 'test-password-editor-123');
        if ($ok !== ($kind === 'valid') || reset_password($pdo, $token, 'test-password-editor-123')) throw new RuntimeException('Recovery expiry/single-use failed.');
    }
    $token = bin2hex(random_bytes(32));
    $insert->execute([hash('sha256', $token), date('Y-m-d H:i:s', time() + 900), 1]);
    ob_start(); imagepng(imagecreatetruecolor(1, 1)); $png = base64_encode(ob_get_clean());
    echo json_encode(['token' => $token, 'png' => $png]);
    exit;
}
$pdo->exec("USE `$name`");
if ($action === 'images') {
    echo json_encode($pdo->query("SELECT id_image, nome FROM tb_image WHERE criado_por <> 'SETUP'")->fetchAll(PDO::FETCH_ASSOC));
}
