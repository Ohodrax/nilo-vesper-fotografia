<?php
declare(strict_types=1);
set_error_handler(function ($severity, $message, $file, $line) { throw new ErrorException($message, 0, $severity, $file, $line); });
function check($condition, string $message): void { if (!$condition) throw new RuntimeException($message); }
foreach (['APP_CONFIG_FILE', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'] as $key) putenv($key);
require_once __DIR__ . '/../bin/config.php';
try { database_options(); throw new LogicException('Missing configuration was accepted'); }
catch (RuntimeException $error) { check(str_contains($error->getMessage(), 'Configuração obrigatória'), 'Missing configuration must fail closed'); }
putenv('DB_HOST=localhost'); putenv('DB_PORT=3306'); putenv('DB_NAME=test_portfolio'); putenv('DB_USER=test_user'); putenv('DB_PASSWORD=test-only');
$config = database_options();
check($config['dsn'] === 'mysql:host=localhost;port=3306;dbname=test_portfolio;charset=utf8mb4', 'DSN must use configured values and utf8mb4');
putenv('DB_HOST=localhost;dbname=other');
try { database_options(); throw new LogicException('DSN injection accepted'); }
catch (RuntimeException $error) { check($error->getMessage() === 'Configuração do banco inválida.', 'Invalid database host'); }
putenv('DB_HOST=localhost'); putenv('APP_URL=https://portfolio.example.com');
$_SERVER['HTTP_HOST'] = 'attacker.example.com';
require_once __DIR__ . '/../bin/_variables.php';
check(ROOT_URL === 'https://portfolio.example.com', 'Host header must not control canonical URL');
require_once __DIR__ . '/../bin/_functions.php';
$crud = new db_crud(); $crud->table = 'test'; $crud->filters = []; $crud->orderby = 'id';
check($crud->table === 'test', 'PHP 8.2+ properties must not emit deprecations');
require_once __DIR__ . '/../Router.php';
class RouteSpy extends Router {
    public string $visited = '';
    protected function home() { $this->visited = 'home'; }
    protected function retratos() { $this->visited = 'retratos'; }
    protected function admin() { $this->visited = 'admin'; }
    protected function recovery() { $this->visited = 'recovery'; }
    public function __call($name, $arguments) { $this->visited = '404'; }
}
$router = new RouteSpy();
foreach (['/' => 'home', '/?q=x' => 'home', '/retratos/' => 'retratos', '/admin?x=1' => 'admin', '/recovery?token=x' => 'recovery', '/missing' => '404', '/run' => '404', '/__call' => '404', '/admin/extra' => '404'] as $uri => $expected) {
    $router->run($uri); check($router->visited === $expected, 'Route failed: ' . $uri);
}
echo 'PHP runtime ' . PHP_VERSION . ': config, DSN, host isolation, autoload, properties and routes passed.' . PHP_EOL;
