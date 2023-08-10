<?php

    header("Content-type: application/json");

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";
    
    $array_return = array("return" => false, "ref" => "");
    $proccess = true;

    if (!array_key_exists("emailsendrecovery", $_POST)) {
        $proccess = false;
        $array_return['ref'] = "E-mail não informado";
    }

    if ($proccess) {
        $emailsendrecovery = $_POST['emailsendrecovery'];

        if (!strpos($emailsendrecovery, "@") || !strpos($emailsendrecovery, ".")) {
            $proccess = false;
            $array_return['ref'] = "E-mail inválido";
        } else {
            $filters = array(
                "id_usuario > 1",
                "email = '".$emailsendrecovery."'",
            );

            $orderby = "id_usuario desc";

            $selectUser = new db_crud();
            $selectUser->table   = "tb_usuario";
            $selectUser->filters = $filters;
            $selectUser->orderby = $orderby;
            $ret_selectUser = $selectUser->default_select_data();

            if (is_array($ret_selectUser)) {
                $id_usuario          = $ret_selectUser[0]['id_usuario'];
                $nome                = $ret_selectUser[0]['nome'];
                $destinyAddr         = $ret_selectUser[0]['email'];
                $status              = $ret_selectUser[0]['status'];
                
                $hashRecovery        = md5($nome.date("YmdHis"));
                
                $link_de_recuperacao = ROOT_URL . "/recovery/" . $hashRecovery;

                if (boolval($status)) {
                    $message = "Olá ".$nome.",<br><br>Segue link para alterar sua senha:<br><br>".$link_de_recuperacao."<br><br>Lembre-se este link irá expirar em 15 minutos!";
                    $subject = "Recuperação de Senha";

                    if (send_email($message, $subject, $destinyAddr)) {
                        $array_data = array(
                            "id_usuario"   => $id_usuario,
                            "hash"         => $hashRecovery,
                            "expire_time"  => date("Y-m-d H:i:s", strtotime("+15 minutes")),
                            "criado_por"   => $nome,
                            "alterado_por" => $nome,
                        );
    
                        $insertNewUser = new db_crud();
                        $insertNewUser->table = "tb_recovery";
                        $insertNewUser->data  = $array_data;
                        $ret_insertNewUser = $insertNewUser->default_insert_data();

                        if ($ret_insertNewUser) {
                            $array_return['return'] = true;
                            $array_return['ref'] = "Verifique seu e-mail";
                        } else {
                            $array_return['return'] = null;
                            $array_return['ref'] = "Erro ao salvar, e-mail enviado";
                        }
                        
                    } else {
                        $proccess = false;
                        $array_return['ref'] = "Erro ao enviar e-mail de recuperação";
                    }

                } else {
                    $proccess = false;
                    $array_return['ref'] = $nome." sua conta está desativada";
                }

            } else {
                $proccess = false;
                $array_return['ref'] = "Conta de usuário não encontrada";
            }
        }
    }

    echo json_encode($array_return);
?>