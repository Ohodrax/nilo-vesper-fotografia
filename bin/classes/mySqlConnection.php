<?php

    class mySqlConnection {

        public function newConnection() {
            // pega a url do site e define as variaveis de conexão de acordo com o servidor detectado
            switch ($_SERVER['HTTP_HOST']) {
                case 'localhost':
                    $mysql_host     = "localhost";
                    $mysql_db       = "paulo_bareta_fotografia";
                    $mysql_username = "root";
                    $mysql_password = "";
                break;
                
                default:
                    $mysql_host     = "";
                    $mysql_db       = "";
                    $mysql_username = "";
                    $mysql_password = "";
                break;
            }
                
            $pdo = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_db, $mysql_username, $mysql_password);


            return $pdo;
        }
    }

?>