<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_admin(false);

    if(session_status() == 1){
        app_session();
    }

    $referencePage = "";
    $reference = "";

    if (isset($_POST['renderContent'])) {
        
        $referencePage = $_POST['renderContent'];

        require_once $_SERVER['DOCUMENT_ROOT'] . "/control/c_getInformations.php";
                
        switch ($referencePage) {
            
            case 'Início':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_home.php";
            break;
            
            case 'Retratos':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_gallery.php";
            break;
            
            case 'Personalidades':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_gallery.php";
            break;
            
            case 'Moda':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_gallery.php";
            break;
            
            case 'Corporativo':
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_gallery.php";
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
                require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/".$_SESSION['firstPage'];
            break;
    
        }
    } else {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/control/c_getInformations.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/ajax/".$_SESSION['firstPage'];
    }

?>