<?php
declare(strict_types=1);

/** Environment wins; optional INI must live outside the document root. */
function app_config(string $key, ?string $default = null): string
{
    $value = getenv($key);
    if ($value !== false && $value !== '') return $value;
    static $private = null;
    if ($private === null) {
        $private = [];
        $configured = getenv('APP_CONFIG_FILE');
        $path = $configured ?: dirname(__DIR__, 2) . '/paulo-bareta.private.ini';
        if (is_file($path)) {
            $real = realpath($path);
            $root = realpath(dirname(__DIR__));
            $normalized = str_replace('\\', '/', $real ?: '');
            $documentRoot = str_replace('\\', '/', $root ?: '');
            if (!$real || !$root || str_starts_with(strtolower($normalized), strtolower($documentRoot . '/')) || !is_readable($real)) {
                throw new RuntimeException('A configuração privada deve estar fora da raiz pública e ser legível.');
            }
            $parsed = parse_ini_file($real, false, INI_SCANNER_RAW);
            if (!is_array($parsed)) throw new RuntimeException('Configuração privada inválida.');
            $private = $parsed;
        } elseif ($configured) {
            throw new RuntimeException('Arquivo de configuração privada indisponível.');
        }
    }
    $value = $private[$key] ?? $default;
    if (!is_string($value) || $value === '') throw new RuntimeException('Configuração obrigatória ausente: ' . $key);
    return $value;
}

function database_options(): array
{
    $host = app_config('DB_HOST');
    $port = app_config('DB_PORT', '3306');
    $name = app_config('DB_NAME');
    if (!preg_match('/^[a-zA-Z0-9.:-]+$/D', $host) || !ctype_digit($port) || (int) $port < 1 || (int) $port > 65535 || !preg_match('/^[a-zA-Z0-9_-]+$/D', $name)) throw new RuntimeException('Configuração do banco inválida.');
    return ['dsn' => 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $name . ';charset=utf8mb4', 'user' => app_config('DB_USER'), 'password' => app_config('DB_PASSWORD')];
}
