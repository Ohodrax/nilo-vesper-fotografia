<?php
require_once __DIR__ . '/RouteSwitch.php';
class Router extends RouteSwitch
{
    public function run(string $requestUri)
    {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $route = is_string($path) ? trim($path, '/') : '__not_found__';
        if (preg_match('/\Arecovery\/[a-f0-9]{64}\z/D', $route)) $route = 'recovery';
        if ($route === '') $route = 'home';
        $routes = ['home', 'retratos', 'personalidades', 'moda', 'corporativo', 'clientes', 'sobre', 'contato', 'login', 'logout', 'recovery', 'admin'];
        if (!in_array($route, $routes, true)) {
            $this->__call($route, []);
            return;
        }
        $this->$route();
    }
}
