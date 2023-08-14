<?php

    header("Content-type: application/json");

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";
    
    $array_return = array("return" => false, "ref" => "");
    $proccess = true;

    if (!array_key_exists("username", $_POST)) {
        $proccess = false;
        $array_return['ref'] = "Usuário não informado";
    }
    if (!array_key_exists("userpassword", $_POST) && $proccess) {
        $proccess = false;
        $array_return['ref'] = "Senha não informada";
    }

    if ($proccess) {
        $username = $_POST['username'];
        $userpass = $_POST['userpassword'];

        if ($username == "" || $userpass == "") {
            $proccess = false;
            $array_return['ref'] = "Usuário /ou senha em branco(s)";
        } else {
            $filters = array(
                "nome = '".$username."' or email = '".$username."'"
            );

            $orderby = "id_usuario desc";

            $selectUser = new db_crud();
            $selectUser->table   = "v_select_user_profile";
            $selectUser->filters = $filters;
            $selectUser->orderby = $orderby;
            $ret_selectUser = $selectUser->default_select_data();

            if (count($ret_selectUser) > 0) {
                if ($ret_selectUser[0]['status'] == 1) {
                    if (password_verify($userpass, $ret_selectUser[0]['senha'])) {

                        session_id(md5($ret_selectUser[0]['id_usuario'].$ret_selectUser[0]['nome'].date("YmdHis")));
                        
                        session_start();

                        $_SESSION['userName'] = $ret_selectUser[0]['nome'];
                        $_SESSION['firstPage'] = $ret_selectUser[0]['first_page'];
                        $_SESSION["showMessage"] = array("return" => "true", "ref" => "Seja bem vindo(a) ".$ret_selectUser[0]['nome']);

                        $array_return['return'] = true;
                        $array_return['ref'] = "Aguarde, por favor... ";
                        
                    } else {
                        $proccess = false;
                        $array_return['ref'] = "Senha incorreta ".$ret_selectUser[0]['nome'];
                    }
                } else {
                    $proccess = false;
                    $array_return['ref'] = "Conta de usuário inativa";
                }
            } else {
                $proccess = false;
                $array_return['ref'] = "Conta de usuário não encontrada";
            }
        }
    }

    echo json_encode($array_return);
?>