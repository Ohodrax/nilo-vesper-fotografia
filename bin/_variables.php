<?php

    switch ($_SERVER['HTTP_HOST']) {
        case 'localhost':
            define("ROOT_URL", "http://localhost");
        break;
        
        case 'dev-pbf.spark-it.com.br':
            define("ROOT_URL", "https://dev-pbf.spark-it.com.br");
        break;
        
        default:
            define("ROOT_URL", "http://localhost");
        break;
    }
?>