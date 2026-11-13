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
            app_session();

            $showMessage = false;

            if (array_key_exists("showMessage", $_SESSION)) {
                $showMessage = true;
                $message     = $_SESSION['showMessage'];
            }

            if (current_user()) {
                header("Location: /admin");
            } else {
                // Keep the CSRF session until an explicit logout.
                require __DIR__ . '/views/admin/login.php';
            }
        }
    }

    protected function logout() {
        require_post();
        $_SESSION = [];
        session_regenerate_id(true);
        json_result(true, 'Você foi desconectado.');
    }

    protected function recovery()
    {
        if (!file_exists(__DIR__ . '/views/admin/recovery_password.php')) {

            // procura a página not found
            if (!file_exists(__DIR__ . '/views/not-found.php')) {
                http_response_code(404);
                echo "Página não encontrada";
            } else {
                require __DIR__ . '/views/not-found.php';
            }
        } else {
            app_session();

            if (current_user()) {
                // Keep the CSRF session until an explicit logout.
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
            app_session();

            $showMessage = false;

            if (array_key_exists("showMessage", $_SESSION)) {
                $showMessage = true;
                $message     = $_SESSION['showMessage'];

                unset($_SESSION['showMessage']);
            }

            if (current_user()) {
                require __DIR__ . '/views/admin/administration_view.php';
            } else {


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