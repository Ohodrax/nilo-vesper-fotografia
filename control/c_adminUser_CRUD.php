<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    session_start();

    $array_return = array("return" => false, "ref" => "");
    $proccess = true;

    if (array_key_exists("userName", $_SESSION)) {
        if (!array_key_exists("formName", $_POST)) {
            $proccess = false;
            $array_return['ref'] = $array_return['ref'] . "Campo nome não encontrado";
        }
        
        if (!array_key_exists("formEmail", $_POST) && $proccess) {
            $proccess = false;
            $array_return['ref'] = $array_return['ref'] . "Campo e-mail não encontrado";
        }
        
        if (!array_key_exists("formPassword", $_POST) && $proccess) {
            $proccess = false;
            $array_return['ref'] = $array_return['ref'] . "Campo senha não encontrado";
        }
    
        if ($proccess) {
    
            if (array_key_exists("formAction", $_POST)) {
                $formName     = $_POST['formName'];
                $formEmail    = $_POST['formEmail'];
                $formPassword = $_POST['formPassword'];
                $formAction   = $_POST['formAction'];
    
                switch ($formAction) {
                    case 'new':
    
                        if (strlen($formName) < 4) {
                            $proccess = false;
                            $array_return['ref'] = "Nome muito curto";
                        }
                        
                        if (!strpos($formEmail, "@") || !strpos($formEmail, ".") && $proccess) {
                            $proccess = false;
                            $array_return['ref'] = "E-mail inválido";
                        }
                        
                        if (strlen($formPassword) < 6 && $proccess) {
                            $proccess = false;
                            $array_return['ref'] = "Senha muito curta";
                        }
    
                        if ($proccess) {
    
                            $array_data = array(
                                "nome"         => $formName,
                                "email"        => $formEmail,
                                "senha"        => password_hash($formPassword, PASSWORD_DEFAULT),
                                "criado_por"   => $_SESSION['userName'],
                                "alterado_por" => $_SESSION['userName'],
                            );
    
                            $insertNewUser = new db_crud();
                            $insertNewUser->table = "tb_usuario";
                            $insertNewUser->data  = $array_data;
                            $ret_insertNewUser = $insertNewUser->default_insert_data();
    
                            $array_return['return'] = $ret_insertNewUser;
                            $array_return['ref'] = boolval($ret_insertNewUser) ? "Novo usuário adicionado!" : "Erro ao adicionar";
                            
                        }
    
                    break;
                    
                    case 'edit':
                        
                        if (!array_key_exists("targetId", $_POST)) {
                            $array_return['ref'] = "Usuário não informado";
                        } else {
                            $id_usuario = $_POST['targetId'];
    
                            if ($id_usuario != 1) {
                                $array_data = array(
                                    "nome"                  => $formName,
                                    "email"                 => $formEmail,
                                    "alterado_por"          => $_SESSION['userName'],
                                    "data_ultima_alteracao" => date("Y-m-d H:i:s")
                                );
        
                                if ($formPassword != "") {
                                    $array_data['senha'] = password_hash($formPassword, PASSWORD_DEFAULT);
                                }
        
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
                        }
    
                    break;
                    
                    case 'exclude':
                        
                        if (!array_key_exists("targetId", $_POST)) {
                            $array_return['ref'] = "Usuário não informado";
                        } else {
                            $id_usuario = $_POST['targetId'];
    
                            if ($id_usuario != 1) {
                                $deleteUser = new db_crud();
                                $deleteUser->id    = $id_usuario;
                                $deleteUser->table = "tb_usuario";
                                $ret_deleteUser=$deleteUser->default_delete_data();
    
                                $array_return['return'] = $ret_deleteUser;
                                $array_return['ref'] = boolval($ret_deleteUser) ? "Conta deletada!" : "Erro ao deletar conta";
    
                            } else {
                                $array_return['ref'] = $array_return['ref'] . "Administrador não pode ser excluido";
                            }
                        }
    
                    break;
                    
                    default:
                        $array_return['ref'] = "Ação desconhecida";
                    break;
                }
            } else {
                $array_return['ref'] = $array_return['ref'] . "Ação não definida #1st";
            }
    
        } else {
            $array_return['ref'] = $array_return['ref'] . "Ação não definida asdasd";
        }
    } else {
        $array_return['ref'] = "Erro, entre novamente para continuar";
    }

    echo json_encode($array_return);
?>