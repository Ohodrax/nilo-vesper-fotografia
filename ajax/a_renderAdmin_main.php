<?php

    if (isset($_POST['renderContent'])) {
        switch ($_POST['renderContent']) {
            
            case 'home':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_home.php";
            break;
            
            case 'pictures':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_pictures.php";
            break;
            
            case 'personalities':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_personalities.php";
            break;
            
            case 'fashion':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_fashion.php";
            break;
            
            case 'corporate':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_corporate.php";
            break;
            
            case 'clients':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_clients.php";
            break;
            
            case 'about':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_about.php";
            break;
            
            case 'contact':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_contact.php";
            break;
            
            default:
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_users.php";
            break;
    
        }
    } else {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_users.php";
    }

?>