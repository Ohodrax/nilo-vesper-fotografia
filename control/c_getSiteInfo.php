<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    $reference = getReference($_SERVER['REQUEST_URI']);
    

    // Requisita as informações para capas, e banners

        $filters = array(
            ['localizacao', '!=', 'gallery'],
            ['referencia', '=', $reference]
        );

        $selectImages = new db_crud();
        $selectImages->table   = "tb_image";
        $selectImages->filters = visible_media_filters($filters);
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
                ['localizacao', '=', 'gallery'],
                ['destaque_carrossel', '=', 1]
            );
    
            $selectImages = new db_crud();
            $selectImages->table   = "tb_image";
            $selectImages->filters = visible_media_filters($filters);
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



$parameters = array_merge(brand_defaults(), array_fill_keys(['input_whatsapp_number', 'input_whatsapp_number_contact', 'input_email', 'input_instagram', 'input_facebook', 'input_linkedin'], ''));
$selectParameters = new db_crud();
$selectParameters->table = 'tb_parametro';
$selectParameters->filters = [['status', '=', 1]];
foreach ($selectParameters->default_select_data() as $parameter) {
    $key = $parameter['input_reference'];
    if (!array_key_exists($key, $parameters)) continue;
    $value = brand_text((string) $parameter['input_value'], $parameters[$key]);
    if ($value !== '') $parameters[$key] = $value;
}
$textarea_capa = $parameters['textarea_capa'];
$textarea_clients_capa = $parameters['textarea_clients_capa'];
$textarea_about_capa = $parameters['textarea_about_capa'];
$input_whatsapp_number = $parameters['input_whatsapp_number'];
$input_whatsapp_number_contact = $parameters['input_whatsapp_number_contact'];
$input_email = $parameters['input_email'];
$input_instagram = $parameters['input_instagram'];
$input_facebook = $parameters['input_facebook'];
$input_linkedin = $parameters['input_linkedin'];
