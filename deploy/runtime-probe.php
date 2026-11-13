<?php
// Copied temporarily under a random name, removed in finally. No phpinfo/secrets output.
ini_set('display_errors', '0');
header('Cache-Control: no-store');
header('Content-Type: application/json');
if (!hash_equals('__PROBE_TOKEN__', $_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '')) {
    http_response_code(404);
    exit;
}
$extensions = ['pdo_mysql', 'fileinfo', 'mbstring', 'openssl'];
$private = [];
$path = getenv('APP_CONFIG_FILE') ?: dirname(__DIR__) . '/paulo-bareta.private.ini';
if (is_file($path)) {
    $real = realpath($path);
    if ($real && !str_starts_with($real, __DIR__ . DIRECTORY_SEPARATOR)) $private = parse_ini_file($real, false, INI_SCANNER_RAW) ?: [];
}
$required = ['APP_URL', 'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'SMTP_HOST', 'SMTP_USER', 'SMTP_PASSWORD', 'SMTP_FROM'];
$missing = array_values(array_filter($required, function ($key) use ($private) { return !(getenv($key) ?: ($private[$key] ?? '')); }));
echo json_encode(['token' => '__PROBE_TOKEN__', 'php' => PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION, 'extensions' => array_values(array_filter($extensions, 'extension_loaded')), 'missing' => $missing]);
