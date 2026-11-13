<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    require_post();
    require_admin(false);

    $array_return = array("return" => false, "ref" => "");
    $proccess     = true;

    if (!array_key_exists("refParamName", $_POST) && $proccess) {
        $proccess            = false;
        $array_return['ref'] = "Referencia não identificada";
    }
    
    if (!array_key_exists("refParamValue", $_POST) && $proccess) {
        $proccess            = false;
        $array_return['ref'] = "Valor não definido";
    }
    
    if ($proccess) {
        $refParamName  = $_POST['refParamName'];
        $refParamValue = $_POST['refParamValue'];
        if (!in_array($refParamName, ['textarea_capa', 'textarea_clients_capa', 'textarea_about_capa', 'input_whatsapp_number', 'input_whatsapp_number_contact', 'input_email', 'input_instagram', 'input_facebook', 'input_linkedin'], true)) json_result(false, 'Campo inválido.', 400);
        if (in_array($refParamName, ['input_instagram', 'input_facebook', 'input_linkedin'], true) && $refParamValue !== '' && safe_url($refParamValue) === '#') json_result(false, 'Informe um endereço HTTP ou HTTPS válido.', 400);
        if ($refParamName === 'input_email' && !filter_var($refParamValue, FILTER_VALIDATE_EMAIL)) json_result(false, 'E-mail inválido.', 400);

        $filters = array(
            ['input_reference', '=', $refParamName]
        );

        $selectReplaceImage = new db_crud();
        $selectReplaceImage->table   = "tb_parametro";
        $selectReplaceImage->filters = $filters;
        $ret_selectReplaceImage = $selectReplaceImage->default_select_data();

        if (count($ret_selectReplaceImage) > 0) {
            
            $idRefText = $ret_selectReplaceImage[0]['id_parametro'];

            $array_data = array(
                "input_value"           => $refParamValue,
                "alterado_por"          => $_SESSION['userName'],
                "data_ultima_alteracao" => date("Y-m-d H:i:s")
            );

            $updateTextParam = new db_crud();
            $updateTextParam->id    = $idRefText;
            $updateTextParam->table = "tb_parametro";
            $updateTextParam->data  = $array_data;
            $ret_updateTextParam = $updateTextParam->default_update_data();

            $array_return['return'] = $ret_updateTextParam;
            $array_return['ref'] = boolval($ret_updateTextParam) ? "Atualizado com sucesso!" : "Erro ao atualizar";

        } else {
            $array_return['ref'] = "Valor não encontrado para mudança";
        }

    }

    echo json_encode($array_return);

?>