<?php

require_once __DIR__ . '/RouteSwitch.php';

class Router extends RouteSwitch
{
    public function run(string $requestUri)
    {
        $route = substr($requestUri, 1);
        
        // verifica se tem variavel na url
        if (strpos($route, "?")) {
            $explodedUri = explode("?", $route);

            // aqui eu pego a pagina que esta sendo solicitada
            $route = $explodedUri[0];
            
            // nesta outra eu pego os valores que estao indo pra ela através de get
            $receivedGetParam = $explodedUri[1];
        }

        if (strpos($route, "/")) {
            $explodedUri = explode("/", $route);

            // aqui eu pego a pagina que esta sendo solicitada
            $route = $explodedUri[0];
            
            // nesta outra eu pego os valores que estao indo pra ela através de get
            $receivedGetParam = $explodedUri[1];
        }

        if ($route === '') {
            $this->home();
        } else {
            $this->$route();
        }
    }
}

?>