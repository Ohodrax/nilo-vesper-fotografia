<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    session_start();

    $array_return = array("return" => false, "ref" => "");

    if (!array_key_exists("targetId", $_POST)) {
        $array_return['ref'] = "Imagem não informado";
    } else {
        $id_image = $_POST['targetId'];

        $filters = array(
            "id_image = ".$id_image
        );

        $selectImage = new db_crud();
        $selectImage->filters = $filters;
        $selectImage->table   = "tb_image";
        $ret_selectImage=$selectImage->default_select_data();

        if (count($ret_selectImage) > 0) {
            
            $refPath = $_SERVER['DOCUMENT_ROOT'] . "/assets/" . $ret_selectImage[0]['referencia'] . "/" . $ret_selectImage[0]['nome'];

            if (unlink($refPath)) {
                $deleteImage = new db_crud();
                $deleteImage->id    = $id_image;
                $deleteImage->table = "tb_image";
                $ret_deleteImage=$deleteImage->default_delete_data();
        
                $array_return['return'] = $ret_deleteImage;
                $array_return['ref'] = boolval($ret_deleteImage) ? "Imagem deletada!" : "Erro ao deletar Imagem";
            } else {
                $array_return['ref'] = "Imagem não excluída";
            }
        } else {
            $array_return['ref'] = "Imagem não localizada no DB";
        }
    }

    echo json_encode($array_return);

?>