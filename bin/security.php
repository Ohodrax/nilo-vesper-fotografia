<?php
function h($value): string { return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function js_value($value): string { return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR); }
function safe_url($value): string {
    if (!is_string($value) || preg_match('/[\x00-\x20\x7f]/', $value)) return '#';
    $url = parse_url($value);
    return $url && in_array(strtolower($url['scheme'] ?? ''), ['https', 'http'], true) && !empty($url['host']) && !isset($url['user']) ? $value : '#';
}
function image_reference($value): string {
    if (!is_string($value) || !in_array($value, ['home', 'pictures', 'personalities', 'fashion', 'corporate', 'clients', 'about', 'contact'], true)) throw new InvalidArgumentException('Referência inválida.');
    return $value;
}
function asset_path($reference, $name): string {
    image_reference($reference);
    if (!is_string($name) || $name === '' || strlen($name) > 255 || basename($name) !== $name || str_contains($name, '\\') || str_contains($name, '/') || preg_match('/[\x00-\x1f]/', $name) || !preg_match('/\.(jpe?g|png)\z/iD', $name)) throw new InvalidArgumentException('Imagem inválida.');
    $root = realpath(dirname(__DIR__) . '/assets');
    $directory = realpath($root . '/' . $reference);
    if (!$root || !$directory || is_link($root . '/' . $reference) || str_replace('\\', '/', $directory) !== str_replace('\\', '/', $root) . '/' . $reference) throw new RuntimeException('Pasta inválida.');
    $path = $directory . '/' . $name;
    if (is_link($path)) throw new RuntimeException('Arquivo inválido.');
    return $path;
}
function app_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params(['httponly' => true, 'secure' => str_starts_with(app_config('APP_URL'), 'https://'), 'samesite' => 'Lax', 'path' => '/']);
    if (!session_start()) throw new RuntimeException('Sessão indisponível.');
}
function csrf_token(): string {
    app_session();
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}
function csrf_valid($token): bool { return is_string($token) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token); }
function json_result(bool $ok, string $message, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['return' => $ok, 'ref' => $message], JSON_THROW_ON_ERROR);
    exit;
}
function require_post(): void {
    app_session();
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { header('Allow: POST'); json_result(false, 'Método não permitido.', 405); }
    if (!csrf_valid($_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['_csrf'] ?? null)) json_result(false, 'Sessão expirada. Atualize a página.', 403);
    foreach ($_POST as $value) if (!is_string($value) || strlen($value) > 20000) json_result(false, 'Dados inválidos.', 400);
}
function post_text(string $key, int $max = 150): string {
    $value = $_POST[$key] ?? '';
    if (!is_string($value) || strlen($value) > $max) json_result(false, 'Dados inválidos.', 400);
    return trim($value);
}
function current_user(): ?array {
    app_session();
    static $checked = false, $user = null;
    if ($checked) return $user;
    $checked = true;
    if (empty($_SESSION['userId']) || ($_SESSION['lastActivity'] ?? 0) < time() - 1800) return null;
    $db = new db_crud();
    $db->table = 'v_select_user_profile';
    $db->filters = [['id_usuario', '=', (int) $_SESSION['userId']], ['status', '=', 1], ['status_perfil', '=', 1]];
    $record = $db->default_select_data()[0] ?? null;
    if (!$record || !hash_equals($_SESSION['authVersion'] ?? '', hash('sha256', $record['senha']))) return null;
    $_SESSION['lastActivity'] = time();
    $_SESSION['userName'] = $record['nome'];
    $_SESSION['firstPage'] = (int) $record['id_perfil'] === 1 ? 'a_renderAdmin_users.php' : 'a_renderAdmin_home.php';
    return $user = $record;
}
function require_admin(bool $users = false): array {
    $user = current_user();
    if (!$user) json_result(false, 'Entre novamente para continuar.', 401);
    if ($users && (int) $user['id_perfil'] !== 1) json_result(false, 'Acesso não permitido.', 403);
    return $user;
}
/** Shared buckets: clearing cookies does not reset attempts; bounded disk usage. */
function rate_limit(string $action, int $limit, int $seconds, string $identity = ''): void {
    $directory = app_config('APP_STATE_DIR', dirname(__DIR__, 2) . '/paulo-bareta-state');
    if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) throw new RuntimeException('Estado privado indisponível.');
    $resolved = realpath($directory);
    $root = str_replace('\\', '/', realpath(dirname(__DIR__)));
    $normalized = str_replace('\\', '/', $resolved ?: '');
    if (!$resolved || str_starts_with(strtolower($normalized) . '/', strtolower($root) . '/')) throw new RuntimeException('Estado deve ficar fora da raiz pública.');
    $bucket = substr(hash('sha256', $action . ':' . $identity . ':' . ($_SERVER['REMOTE_ADDR'] ?? 'local')), 0, 3);
    $file = fopen($resolved . '/' . $action . '-' . $bucket . '.json', 'c+');
    if (!$file || !flock($file, LOCK_EX)) throw new RuntimeException('Limitador indisponível.');
    try {
        $state = json_decode(stream_get_contents($file), true);
        if (!is_array($state) || ($state['until'] ?? 0) <= time()) $state = ['until' => time() + $seconds, 'count' => 0];
        $allowed = ++$state['count'] <= $limit;
        ftruncate($file, 0); rewind($file); fwrite($file, json_encode($state)); fflush($file);
    } finally { flock($file, LOCK_UN); fclose($file); }
    if (!$allowed) { header('Retry-After: ' . $seconds); json_result(false, 'Muitas tentativas. Aguarde e tente novamente.', 429); }
}
function security_bootstrap(): void {
    ini_set('display_errors', '0');
    if (PHP_SAPI === 'cli') return;
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: no-referrer');
    header("Content-Security-Policy: base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'");
    header('Cache-Control: no-store');
    set_exception_handler(function (Throwable $error): void {
        error_log('request_failed:' . get_class($error));
        json_result(false, $error instanceof InvalidArgumentException ? 'Dados inválidos.' : 'Não foi possível concluir a solicitação.', $error instanceof InvalidArgumentException ? 400 : 500);
    });
    app_session();
}
