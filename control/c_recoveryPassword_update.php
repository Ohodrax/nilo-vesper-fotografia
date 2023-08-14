<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    session_start();

    $array_return = array("return" => false, "ref" => "");
    $proccess = true;

    if (!array_key_exists("formNewPassword", $_POST)) {
        $array_return['ref'] = "Campo de nova senha não identificada";
        $proccess = false;
    }

    if (!array_key_exists("formConfirmPassword", $_POST) && !$proccess) {
        $array_return['ref'] = "Campo de confirmar senha não identificada";
        $proccess = false;
    }

    if (!array_key_exists("formRaid", $_POST) && !$proccess) {
        $array_return['ref'] = "Informações não identificadas";
        $proccess = false;
    }

    if ($proccess) {
        $password        = $_POST['formNewPassword'];
        $confirmPassword = $_POST['formConfirmPassword'];
        $array_raid      = json_decode(base64_decode($_POST['formRaid']), true);
        
        if ($password === $confirmPassword) {
            
            $id_usuario = intval($array_raid['id_usuario']);
            $hash       = $array_raid['hash'];

            $filters = array(
                "id_usuario = '".$id_usuario."'",
                "hash = '".$hash."'"
            );
        
            $selectData = new db_crud();
            $selectData->table   = "tb_recovery";
            $selectData->filters = $filters;
            $ret_selectData = $selectData->default_select_data();

            if (count($ret_selectData) == 0) {
                $array_return['ref'] = "Link não encontrado";
                $proccess = false;

                $_SESSION["showMessage"] = array("return" => "false", "ref" => "Link não encontrado");
            }

            if (date("Y-m-d H:i:s") > date("Y-m-d H:i:s", strtotime($ret_selectData[0]['expire_time'])) && !$proccess) {
                $array_return['ref'] = "Este link está expirado";
                $proccess = false;

                $_SESSION["showMessage"] = array("return" => "false", "ref" => "Este link está expirado");
            }

            if ($ret_selectData[0]['status'] == 0 && !$proccess) {
                $array_return['ref'] = "Este link já foi utilizado";
                $proccess = false;
                
                $_SESSION["showMessage"] = array("return" => "false", "ref" => "Este link já foi utilizado");
            }

            if ($proccess) {
                $array_data = array(
                    "senha"                 => password_hash($password, PASSWORD_DEFAULT),
                    "alterado_por"          => "UPDATE_PASSWORD",
                    "data_ultima_alteracao" => date("Y-m-d H:i:s")
                );

                $updatePassword = new db_crud();
                $updatePassword->id    = $id_usuario;
                $updatePassword->table = "tb_usuario";
                $updatePassword->data  = $array_data;
                $ret_updatePassword = $updatePassword->default_update_data();

                $array_return['return'] = $ret_updatePassword;
                $array_return['ref']    = boolval($ret_updatePassword) ? "Senha atualizada!" : "Erro ao atualizar senha";

                $array_data = array(
                    "status"                => 0,
                    "alterado_por"          => "UPDATE_PASSWORD",
                    "data_ultima_alteracao" => date("Y-m-d H:i:s")
                );

                $updatePassword = new db_crud();
                $updatePassword->id    = $ret_selectData[0]['id_recovery'];
                $updatePassword->table = "tb_recovery";
                $updatePassword->data  = $array_data;
                $ret_updatePassword = $updatePassword->default_update_data();

                $_SESSION["showMessage"] = array("return" => "true", "ref" => "Senha atualizada!");
            }

        } else {
            $array_return['ref'] = "As senhas não são iguais";
        }

    }

    echo json_encode($array_return);
?>