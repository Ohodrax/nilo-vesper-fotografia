<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    switch ($referencePage) {
        case 'Início':
            $reference = "home";
        break;
        case 'Retratos':
            $reference = "pictures";
        break;
        
        case 'Personalidades':
            $reference = "personalities";
        break;
        
        case 'Moda':
            $reference = "fashion";
        break;
        
        case 'Corporativo':
            $reference = "corporate";
        break;

        case 'Clientes':
            $reference = "clients";
        break;
        
        case 'Sobre':
            $reference = "about";
        break;
        
        case 'Contato':
            $reference = "contact";
        break;

        default:
            if ($_SESSION['firstPage'] == "a_renderAdmin_users.php") {
                $reference = "users";
            } 
            if ($_SESSION['firstPage'] == "a_renderAdmin_home.php") {
                $reference = "home";
            }
        break;
    }

    if ($reference == "users") {
        $filters = array(
            "id_usuario > 0"
        );
    
        $orderby = "id_usuario asc";
    
        $selectUser = new db_crud();
        $selectUser->table   = "v_select_user_profile";
        $selectUser->filters = $filters;
        $selectUser->orderby = $orderby;
        $ret_selectUser = $selectUser->default_select_data();
    }

    $filters = array(
        "id_image > 0",
        "referencia = '".$reference."'"
    );

    $orderby = "id_image desc";

    $selecImages = new db_crud();
    $selecImages->table   = "tb_image";
    $selecImages->filters = $filters;
    $selecImages->orderby = $orderby;
    $ret_selecImages = $selecImages->default_select_data();

    $filters = array(
        "id_parametro > 0",
        "page_ref = '".$reference."'"
    );

    $selecParametro = new db_crud();
    $selecParametro->table   = "tb_parametro";
    $selecParametro->filters = $filters;
    $ret_selecParametro = $selecParametro->default_select_data();

    if ($reference != "contact") {
        $textareaCapa = "";
    
        if (count($ret_selecParametro) > 0) {
            $textareaCapa = $ret_selecParametro[0]['input_value'];
        }
    } else {
        if (count($ret_selecParametro) == 0) {
            $input_whatsapp_number         = "";
            $input_whatsapp_number_contact = "";
            $input_email                   = "";
            $input_instagram               = "";
            $input_facebook                = "";
            $input_linkedin                = "";
        } else {
            foreach ($ret_selecParametro as $key => $value) {
                if ($value['input_reference'] == "input_whatsapp_number") {
                    $input_whatsapp_number = $value['input_value'];
                }
                if ($value['input_reference'] == "input_whatsapp_number_contact") {
                    $input_whatsapp_number_contact = $value['input_value'];
                }
                if ($value['input_reference'] == "input_email") {
                    $input_email = $value['input_value'];
                }
                if ($value['input_reference'] == "input_instagram") {
                    $input_instagram = $value['input_value'];
                }
                if ($value['input_reference'] == "input_facebook") {
                    $input_facebook = $value['input_value'];
                }
                if ($value['input_reference'] == "input_linkedin") {
                    $input_linkedin = $value['input_value'];
                }
            }
        }
    }

    $numCarrosselImages = 0;
    $numCapaImage       = 0;
    $numBanner1         = 0;
    $numBanner2         = 0;

    $capaImage = "default-image.jpg";
    $banner1   = "default-image.jpg";
    $banner2   = "default-image.jpg";
                
    for ($i=0; $i < count($ret_selecImages); $i++) {
        // conmta apenas o num de imagens do carrossel da home 
        if ($ret_selecImages[$i]['localizacao'] == "carrossel_principal") {
            $numCarrosselImages++;
        }
        if ($ret_selecImages[$i]['localizacao'] == "capa_principal") {
            $capaImage = $ret_selecImages[$i]['referencia'] . "/" . $ret_selecImages[$i]['nome'];
        }
        if ($ret_selecImages[$i]['localizacao'] == "banner_1") {
            $banner1 = $ret_selecImages[$i]['referencia'] . "/" . $ret_selecImages[$i]['nome'];
        }
        if ($ret_selecImages[$i]['localizacao'] == "banner_2") {
            $banner2 = $ret_selecImages[$i]['referencia'] . "/" . $ret_selecImages[$i]['nome'];
        }
    }

    $printMode = $numCarrosselImages <= 1 ? "Imagem única" : "Carrossel de imagens";
?>