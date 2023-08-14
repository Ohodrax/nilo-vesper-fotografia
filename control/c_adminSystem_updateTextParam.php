<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    session_start();

    $array_return = array("return" => false, "ref" => "");
    $proccess     = true;

    if (!array_key_exists("refParamName", $_POST) && $proccess) {
        $proccess            = false;
        $array_return['ref'] = "Referencia não identificada";

        $array_return["debug"] = $_POST;
    }
    
    if (!array_key_exists("refParamValue", $_POST) && $proccess) {
        $proccess            = false;
        $array_return['ref'] = "Valor não definido";

        $array_return["debug"] = $_POST;
    }
    
    if ($proccess) {
        $refParamName  = $_POST['refParamName'];
        $refParamValue = $_POST['refParamValue'];

        $filters = array(
            "input_reference = '".$refParamName."'"
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