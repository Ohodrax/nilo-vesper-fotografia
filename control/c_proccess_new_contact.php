<?php
    header('Content-type: application/json');

    include $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    $array_return = array("return" => false, "ref" => "");
    $proccess = true;

    if (!array_key_exists("form_inputNome", $_POST)) {
        $proccess = false;
        $array_return['ref'] = "Campo Nome não definido, atualize a página";
    }
    
    if (!array_key_exists("form_inputEmail", $_POST) && $proccess) {
        $proccess = false;
        $array_return['ref'] = "Campo E-mail não definido, atualize a página";
    }
    
    if (!array_key_exists("form_inputTelefone", $_POST) && $proccess) {
        $proccess = false;
        $array_return['ref'] = "Campo Telefone não definido, atualize a página";
    }

    if (!array_key_exists("form_inputMensagem", $_POST) && $proccess) {
        $proccess = false;
        $array_return['ref'] = "Campo Mensagem não definido, atualize a página";
    }

    if ($proccess) {
        $nome     = $_POST['form_inputNome'];
        $email    = $_POST['form_inputEmail'];
        $telefone = $_POST['form_inputTelefone'];
        $mensagem = $_POST['form_inputMensagem'];
        
        if ($nome == "") {
            $proccess = false;
            $array_return['ref'] = "Campo Nome inválido";
        }
        if ($email == "" || !strpos($email, "@") || !strpos($email, ".")) {
            $proccess = false;
            $array_return['ref'] = "Campo E-mail inválido";
        }
        if ($mensagem == "") {
            $proccess = false;
            $array_return['ref'] = "Campo Mensagem inválido";
        }

        if ($proccess) {
            $array_data = array(
                "nome"         => $nome,
                "email"        => $email,
                "telefone"     => $telefone,
                "mensagem"     => $mensagem,
                "criado_por"   => "SISTEM",
                "alterado_por" => "SISTEM",
            );

            $insertData = new db_crud();
            $insertData->table = "tb_lead";
            $insertData->data  = $array_data;
            $ret_insertData = $insertData->default_insert_data();

            if ($ret_insertData) {

                $filters = array(
                    "input_reference = 'input_email'"
                );

                $selectEmail = new db_crud();
                $selectEmail->filters = $filters;
                $selectEmail->table = "tb_parametro";
                $ret_selectEmail = $selectEmail->default_select_data();

                $destinyAddr = $ret_selectEmail[0]['input_value'];                
                $subject = "Novo contato";
                $message = "
                    Olá, 
                    <br><br>
                    Segue novo registro de contato feito pelo site:
                    <br><br>
                    Nome: ".$nome."<br>
                    E-mail: ".$email."<br>
                    Telefone: ".$telefone."<br>
                    Mensagem: ".$mensagem."<br>
                ";

                send_email($message, $subject, $destinyAddr);

                $array_return['return'] = true;
                $array_return['ref'] = "Contato enviado com sucesso!";

            } else {
                $array_return['ref'] = "Não foi possível salvar seu contato";
            }
        }
    }

    echo json_encode($array_return);

?>