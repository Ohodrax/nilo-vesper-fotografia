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

    protected function login()
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
            session_start();

            $showMessage = false;

            if (array_key_exists("showMessage", $_SESSION)) {
                $showMessage = true;
            }

            if(array_key_exists("userName", $_SESSION)) {
                header("Location: /admin");
            } else {
                session_destroy();
                require __DIR__ . '/views/admin/login.php';
            }
        }
    }

    protected function logout() {
        session_start();

        session_unset();
        session_destroy();
        session_abort();
    
        session_start();

        $_SESSION["showMessage"] = true;

        header("Location: /login");
    }

    protected function recovery()
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
            session_start();

            if(array_key_exists("userName", $_SESSION)) {
                session_destroy();
            }

            require __DIR__ . '/views/admin/recovery_password.php';
        }
    }

    protected function admin()
    {
        if (!file_exists(__DIR__ . '/views/admin/administration_view.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            session_start();

            if (array_key_exists("userName", $_SESSION)) {
                require __DIR__ . '/views/admin/administration_view.php';
            } else {
                session_unset();
                session_destroy();
                session_abort();

                header("Location: /login");
            }
        }
    }
    
    public function __call($name, $arguments)
    {
        http_response_code(404);
        require __DIR__ . '/views/not-found.php';
    }
}

?>