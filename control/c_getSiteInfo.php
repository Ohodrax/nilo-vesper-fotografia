<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    $reference = getReference($_SERVER['REQUEST_URI']);
    

    // Requisita as informações para capas, e banners

        $filters = array(
            "localizacao not in ('gallery')",
            "referencia = '".$reference."'"
        );

        $selectImages = new db_crud();
        $selectImages->table   = "tb_image";
        $selectImages->filters = $filters;
        $ret_selectImages = $selectImages->default_select_data();

        $arrayCarrosselImages = array();
        $capaPrincipal        = "default-image.jpg";
        $banner1              = "default-image.jpg";
        $banner2              = "default-image.jpg";

        foreach ($ret_selectImages as $key => $value) {
            if ($value['localizacao'] == "carrossel_principal") {
                array_push($arrayCarrosselImages, $value['nome']);
            }
            if ($value['localizacao'] == "capa_principal") {
                $capaPrincipal = $value['nome'];
            }
            if ($value['localizacao'] == "banner_1") {
                $banner1 = $value['nome'];
            }
            if ($value['localizacao'] == "banner_2") {
                $banner2 = $value['nome'];
            }
        }

    // Requisita as informações para capas, e banners


    // Se a página for a home amigo.... seleciona os destaques de todas as galerias...

        if ($reference == "home") {
            $filters = array(
                "localizacao        = 'gallery'",
                "destaque_carrossel = 1"
            );
    
            $selectImages = new db_crud();
            $selectImages->table   = "tb_image";
            $selectImages->filters = $filters;
            $ret_selectImages = $selectImages->default_select_data();

            $arrayCarrosselRetratos       = array();
            $arrayCarrosselPersonalidades = array();
            $arrayCarrosselModa           = array();
            $arrayCarrosselCorporativo    = array();

            if (count($ret_selectImages) > 0) {
                foreach ($ret_selectImages as $key => $value) {
                    if ($value['referencia'] == "pictures") {
                        array_push($arrayCarrosselRetratos, $value['nome']);
                    }
                    if ($value['referencia'] == "personalities") {
                        array_push($arrayCarrosselPersonalidades, $value['nome']);
                    }
                    if ($value['referencia'] == "fashion") {
                        array_push($arrayCarrosselModa, $value['nome']);
                    }
                    if ($value['referencia'] == "corporate") {
                        array_push($arrayCarrosselCorporativo, $value['nome']);
                    }                    
                }
            }
        }

    // Se a página for a home amigo.... seleciona os destaques de todas as galerias...


    // Requisita as informações para contato

        $filters = array(
            "id_parametro > 0",
            "status       = 1",
        );

        $selecParametro = new db_crud();
        $selecParametro->table   = "tb_parametro";
        $selecParametro->filters = $filters;
        $ret_selecParametro = $selecParametro->default_select_data();

        if (count($ret_selecParametro) == 0) {
            $textarea_capa                 = "";
            $textarea_clients_capa         = "";
            $textarea_about_capa           = "";
            $input_whatsapp_number         = "";
            $input_whatsapp_number_contact = "";
            $input_email                   = "";
            $input_instagram               = "";
            $input_facebook                = "";
            $input_linkedin                = "";
        } else {
            foreach ($ret_selecParametro as $key => $value) {
                if ($value['input_reference'] == "textarea_capa") {
                    $textarea_capa = $value['input_value'];
                }
                if ($value['input_reference'] == "textarea_clients_capa") {
                    $textarea_clients_capa = $value['input_value'];
                }
                if ($value['input_reference'] == "textarea_about_capa") {
                    $textarea_about_capa = $value['input_value'];
                }
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

    // Requisita as informações para contato

?>