<?php

    class mySqlConnection {

        public function newConnection() {
            // pega a url do site e define as variaveis de conexão de acordo com o servidor detectado
            switch ($_SERVER['HTTP_HOST']) {
                case 'localhost':
                    $mysql_host     = "localhost";
                    $mysql_db       = "db_jfh_assessoria";
                    $mysql_username = "root";
                    $mysql_password = "]l1[h/c50kJ-YsAd";
                break;

                case 'jfhassessoria.com':
                    $mysql_host     = "192.185.177.49";
                    $mysql_db       = "jfhcre23_jfh_assessoria";
                    $mysql_username = "jfhcre23_superus_jfh_assessoria";
                    $mysql_password = "6vq!dKO,QNAV";
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