<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";
    
    session_start();
    
    $array_return = array("return" => false, "ref" => "");

    if (array_key_exists("userName", $_SESSION)) {
    
        $id_usuario = $_POST['targetId'];
        $setStatus = $_POST['setStatus'] == "true" ? 1 : 0;

        if ($id_usuario != 1) {
            $array_data = array(
                "status"                => $setStatus,
                "alterado_por"          => "SYSTEM",
                "data_ultima_alteracao" => date("Y-m-d H:i:s")
            );

            $updateUser = new db_crud();
            $updateUser->id    = $id_usuario;
            $updateUser->table = "tb_usuario";
            $updateUser->data  = $array_data;
            $ret_updateUser = $updateUser->default_update_data();

            $array_return['return'] = $ret_updateUser;
            $array_return['ref'] = boolval($ret_updateUser) ? "Conta atualizada!" : "Erro ao atualizar conta";
        } else {
            $array_return['ref'] = $array_return['ref'] . "Administrador não pode ser alterado";
        }
    } else {
        $array_return['ref'] = "Erro, entre novamente para continuar";
    }

    echo json_encode($array_return);
?>