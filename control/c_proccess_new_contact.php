<?php
    header('Content-type: application/json');

    include('../bin/_environment.php');

    $nome_completo     = $_POST['nome'];
    $cpf               = $_POST['cpf'];
    $email             = $_POST['email'];
    $whatsapp          = $_POST['whatsapp'];
    $mensagem          = $_POST['mensagem'];
    $valor             = $_POST['valor_desejado'];
    $parcelas          = $_POST['parcelas'];
    $carencia          = $_POST['carencia'];
    $checkbox_consulta = $_POST['check_consulta'];
    $checkbox_infos    = $_POST['check_infos'];
    $process           = true;

    // função para verificar se o contato já foi enviado anteriormente
    $getContact = new contact();
    $getContact->cpf      = $cpf;
    $getContact->whatsapp = $whatsapp;
    $getContact->email    = $email;
    $ret_getContact=$getContact->getContact();

    if (count($ret_getContact) == 0) {

        // validação individual de todos os campos
            $array_invalid_inputs = array();

            if (!validation($cpf)) {
                $process = false;
        
                array_push($array_invalid_inputs, "CPF");
            }
        
            if (!strpos($email, "@") && !strpos($email, ".")) {
                $process = false;
                
                array_push($array_invalid_inputs, "E-mail");
            }
        
            if (strlen($whatsapp) < 14) {
                $process = false;
                
                array_push($array_invalid_inputs, "Whatsapp");
            }

            $valor_desejado = str_replace(",", ".", str_replace(".", "", str_replace("R$ ", "", $valor)));

            if (intval(preg_replace('/[^0-9]/', '', substr_replace($valor_desejado, "", -3))) == 0) {
                $process = false;
                
                array_push($array_invalid_inputs, "Valor Desejado");
            }
        
            if (strlen($nome_completo) < 3) {
                $process = false;
                
                array_push($array_invalid_inputs, "Nome");
            }
        
            if (!boolval(intval($checkbox_consulta))) {
                $process = false;
                
                array_push($array_invalid_inputs, "Checkbox sobre consulta");
            }
        
            if (!boolval(intval($checkbox_infos))) {
                $process = false;
                
                array_push($array_invalid_inputs, "Checkbox sobre veracidade");
            }
        // fim da validação
    
        // se todos estiverem validados vai salvar no banco de dados
        if ($process) {
            // salvando no banco de dados
                $newContact = new contact();
                $newContact->nome_completo     = $nome_completo;
                $newContact->cpf               = $cpf;
                $newContact->email             = $email;
                $newContact->whatsapp          = $whatsapp;
                $newContact->mensagem          = $mensagem;
                $newContact->valor_desejado    = $valor_desejado;
                $newContact->parcelas          = $parcelas;
                $newContact->carencia          = $carencia;
                $newContact->checkbox_consulta = $checkbox_consulta;
                $newContact->checkbox_infos    = $checkbox_infos;
                $ret_newContact = $newContact->saveNewContact();
            // fim do salvamento
    
            // se foi salvo corretamente vai enviar um e-mail com o contato
            if ($ret_newContact) {

                // Monta a lista de campos para ser enviado no email
                $array_inputMessage = array(
                    "Nome Completo"       => $nome_completo,
                    "CPF"                 => $cpf,
                    "E-mail"              => $email,
                    "Whatsapp"            => $whatsapp,
                    "Mensagem"            => $mensagem,
                    "Valor Desejado"      => $valor,
                    "Parcelas"            => $parcelas,
                    "Carência"            => $carencia,
                    "Checkbox Consulta"   => boolval(intval($checkbox_consulta)) ? "Sim" : "Não",
                    "Checkbox Veracidade" => boolval(intval($checkbox_infos)) ? "Sim" : "Não"
                );

                $array_nome = explode(" ", $nome_completo);
                $nome       = $array_nome[0];

                // chama a função de enviar a mensagem
                $sendMessage = send_newContact_email($array_inputMessage);
                
                if ($sendMessage) {
                    $array_return = array("return" => true, "message" => "Obrigado, ".$nome."! Em breve vamos entrar em contato!");
                } else {
                    $array_return = array("return" => false, "message" => "Não foi possível finalizar sua solicitação.");
                }                
            } else {
                $array_return = array("return" => false, "message" => "Não foi possível completar sua solicitação.");
            }        
        } else {
            // verifica se tem algum campo inválido e monta uma lista com os itens invalidos
            if (count($array_invalid_inputs) > 0) {
                $listItens = "<ul>";
                for ($i=0; $i < count($array_invalid_inputs); $i++) { 
                    $listItens .= "<li>".$array_invalid_inputs[$i]."</li>";
                }
                $listItens .= "</ul>";
                
                $array_return = array("return" => false, "message" => "Um ou mais itens estão inválidos: " . $listItens);
            } else {
                $array_return = array("return" => false, "message" => "Processo não finalizado.");
            }
        }
    } else {
        $array_return = array("return" => true, "message" => "Recebemos seu contato e estamos processando seus dados!");
    }

    echo json_encode($array_return);

?>