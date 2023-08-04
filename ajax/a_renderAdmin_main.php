<?php

    if (isset($_POST['renderContent'])) {
        switch ($_POST['renderContent']) {
            
            case 'Início':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_home.php";
            break;
            
            case 'Retratos':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_pictures.php";
            break;
            
            case 'Personalidades':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_personalities.php";
            break;
            
            case 'Moda':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_fashion.php";
            break;
            
            case 'Corporativo':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_corporate.php";
            break;
            
            case 'Clientes':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_clients.php";
            break;
            
            case 'Sobre':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_about.php";
            break;
            
            case 'Contato':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_contact.php";
            break;
            
            default:
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_users.php";
            break;
    
        }
    } else {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_users.php";
        // require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_home.php";
    }

?>