<?php

abstract class RouteSwitch
{
    protected function home()
    {
        if (!file_exists(__DIR__ . '/views/home.php')) {
            
            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            require __DIR__ . '/views/home.php';
        }
    }

    protected function retratos()
    {
        if (!file_exists(__DIR__ . '/views/pictures.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            require __DIR__ . '/views/pictures.php';
        }
    }

    protected function personalidades()
    {
        if (!file_exists(__DIR__ . '/views/personalities.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            require __DIR__ . '/views/personalities.php';
        }
    }

    protected function moda()
    {
        if (!file_exists(__DIR__ . '/views/fashion.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            require __DIR__ . '/views/fashion.php';
        }
    }

    protected function corporativo()
    {
        if (!file_exists(__DIR__ . '/views/corporate.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            require __DIR__ . '/views/corporate.php';
        }
    }

    protected function clientes()
    {
        if (!file_exists(__DIR__ . '/views/clients.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            require __DIR__ . '/views/clients.php';
        }
    }

    protected function sobre()
    {
        if (!file_exists(__DIR__ . '/views/contact.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            require __DIR__ . '/views/about.php';
        }
    }

    protected function contato()
    {
        if (!file_exists(__DIR__ . '/views/contact.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            require __DIR__ . '/views/contact.php';
        }
    }

    protected function admin()
    {
        if (!file_exists(__DIR__ . '/views/admin/login.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            require __DIR__ . '/views/admin/login.php';
        }
    }
    
    public function __call($name, $arguments)
    {
        http_response_code(404);
        require __DIR__ . '/views/not-found.php';
    }
}

?>