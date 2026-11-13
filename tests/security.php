<?php
declare(strict_types=1);
require_once __DIR__ . '/../bin/security.php';
require_once __DIR__ . '/../bin/classes/mySqlConnection.php';
require_once __DIR__ . '/../bin/classes/db_crud.php';
function ensure($ok, $name) { if (!$ok) throw new RuntimeException($name); }
function rejects(callable $fn, string $name) {
    try { $fn(); } catch (InvalidArgumentException $error) { return; }
    throw new RuntimeException($name);
}
$pdo = new PDO('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$pdo->exec('CREATE TABLE tb_usuario (id_usuario INTEGER PRIMARY KEY, nome TEXT, email TEXT)');
$pdo->exec("INSERT INTO tb_usuario VALUES (1, 'Owner', 'owner@example.invalid'), (2, 'Editor', 'editor@example.invalid')");
$db = new db_crud($pdo);
$db->table = 'tb_usuario';
$db->filters = [['nome', '=', "' OR 1=1 --"]];
ensure($db->default_select_data() === [], 'SQL injection through filter');
$db->filters = [['any' => [['nome', '=', 'missing'], ['email', '=', 'editor@example.invalid']]]];
ensure(count($db->default_select_data()) === 1, 'Grouped OR binds separate values');
$db->id = '1 OR 1=1';
rejects(fn() => $db->default_delete_data(), 'DELETE id injection');
$db->data = ['nome' => 'changed'];
rejects(fn() => $db->default_update_data(), 'UPDATE id injection');
$db->id = 2;
$payload = '</textarea><img src=x onerror=alert(1)>';
$db->data = ['nome' => $payload];
ensure($db->default_update_data(), 'Legitimate update');
$db->filters = [['id_usuario', '=', 2]];
ensure($db->default_select_data()[0]['nome'] === $payload, 'Store data without lossy input escaping');
ensure(!str_contains(h($payload), '<') && str_contains(h($payload), '&lt;'), 'Encode HTML text and attributes on output');
ensure(!str_contains(js_value('</script>'), '</script>'), 'Encode inline JavaScript separately');
$db->filters = ["id_usuario = 1 OR 1=1"];
rejects(fn() => $db->default_select_data(), 'Reject raw SQL predicates');
$db->filters = [];
$db->orderby = 'id_usuario desc; DROP TABLE tb_usuario';
rejects(fn() => $db->default_select_data(), 'ORDER BY injection');
$db->orderby = null;
$db->table = 'tb_usuario; DROP TABLE tb_usuario';
rejects(fn() => $db->default_select_data(), 'Table injection');
foreach (['javascript:alert(1)', 'data:text/html,x', '//evil.example', "https://ok.example\n.evil", 'https://user@evil.example'] as $url) ensure(safe_url($url) === '#', 'Unsafe URL');
ensure(safe_url('https://example.com/path') === 'https://example.com/path', 'Valid HTTPS link');
foreach ([['../bin', 'file.jpg'], ['pictures', '../file.jpg'], ['pictures', 'file.php'], ['pictures', 'a\\b.jpg']] as [$ref, $name]) rejects(fn() => asset_path($ref, $name), 'Upload path traversal');
$_SESSION = ['csrf' => str_repeat('a', 64)];
ensure(csrf_valid(str_repeat('a', 64)), 'Valid CSRF');
foreach ([null, [], '', str_repeat('b', 64)] as $bad) ensure(!csrf_valid($bad), 'Invalid CSRF');
echo "Security tests: parameterized CRUD, XSS contexts, URLs, paths and CSRF passed.\n";
