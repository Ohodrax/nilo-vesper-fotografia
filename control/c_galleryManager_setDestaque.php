<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";
    
    require_post();
    require_admin(false);
    
    $array_return = array("return" => false, "ref" => "");

    if (array_key_exists("userName", $_SESSION)) {
    
        $id_image = $_POST['targetId'];

        $filters = array(
            ['id_image', '=', $id_image]
        );

        $selectImage = new db_crud();
        $selectImage->filters = $filters;
        $selectImage->table   = "tb_image";
        $ret_selectImage = $selectImage->default_select_data();

        if (count($ret_selectImage) > 0) {
            $array_data = array(
                "destaque_carrossel"    => intval(!boolval($ret_selectImage[0]['destaque_carrossel'])),
                "alterado_por"          => $_SESSION['userName'],
                "data_ultima_alteracao" => date("Y-m-d H:i:s")
            );

            $updateUser = new db_crud();
            $updateUser->id    = $id_image;
            $updateUser->table = "tb_image";
            $updateUser->data  = $array_data;
            $ret_updateUser = $updateUser->default_update_data();

            $array_return['return'] = $ret_updateUser;
            $array_return['ref'] = boolval($ret_updateUser) ? "Imagem destacada!" : "Erro ao salvar imagem destaque";
        } else {
            $array_return['ref'] = $array_return['ref'] . "Imagem não encontrada";
        }
    } else {
        $array_return['ref'] = "Erro, entre novamente para continuar";
    }

    echo json_encode($array_return);
?>