<?php
require_once __DIR__ . '/config.php';
$appUrl = rtrim(app_config('APP_URL'), '/');
$parsedUrl = parse_url($appUrl);
if (!$parsedUrl || !in_array($parsedUrl['scheme'] ?? '', ['https', 'http'], true) || empty($parsedUrl['host']) || isset($parsedUrl['user']) || isset($parsedUrl['query']) || isset($parsedUrl['fragment']) || !empty($parsedUrl['path'])) {
    throw new RuntimeException('APP_URL deve ser a origem pública do site, sem caminho.');
}
define('ROOT_URL', $appUrl);
