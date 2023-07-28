<?php
    include('../vendor/autoload.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // ler todas as classes automaticamente
    spl_autoload_register(function ($className) {
        include 'classes/'.$className.'.php';
    });

    // Enviar email com novo contato passando apenas os campos do formulário
    function send_newContact_email($arrayCampos) {

        $sended = true;

        try {
            $mail = new PHPMailer(true);

            /*Server Config*/
    
                // $mail->SMTPDebug = 3;
                $mail->isSMTP();
                $mail->Host       = 'smtp.titan.email';
                $mail->SMTPAuth   = true;
                $mail->SMTPSecure = 'ssl';
    
                //usuario e senha para autenticacao
                $mail->Username = 'contato@jfhassessoria.com';
                $mail->Password = 'jfhUnderground#6';
                $mail->Port     = 465;
                $mail->CharSet  = 'utf-8';
    
            /*End of Server Config*/
    
            /*Recipients*/
    
                //email de quem esta enviando email
                $mail->setFrom('contato@jfhassessoria.com');
                //destinatario email
                $mail->addAddress('contato@jfhassessoria.com', 'Emprestimos');//(strtolower($_POST['email']), strtolower(ucfirst($_POST['nome'])));
    
            /*End of Recipients*/
    
            /*Content*/
    
                //corpo do email como html_entity_decode
                $mail->isHTML(true);
                //assunto do email
                $mail->Subject = 'Novo contato - Site';
                //corpo do email
    
                $message = "";
    
                foreach ($arrayCampos as $key => $value) {
                    $message .=  $key . ": " . $value . "<br>";
                }
    
                $mail->Body = $message;
    
            /*End of Content*/
    
            $mail->send();

        } catch (Exception $th) {
            $sended = false;

            throw $th->getMessage();
        }

        return $sended;
    }

    function validation($cpf) {
        $digitos_1 = array(
            '10' => substr($cpf, 0, -13),
            '9' => substr($cpf, 1, -12),
            '8' => substr($cpf, 2, -11),
            '7' => substr($cpf, 4, -9),
            '6' => substr($cpf, 5, -8),
            '5' => substr($cpf, 6, -7),
            '4' => substr($cpf, 8, -5),
            '3' => substr($cpf, 9, -4),
            '2' => substr($cpf, 10, -3)
        );

        $calcX=0;
        for ($i=2; $i < (count($digitos_1)+2); $i++) { 
        #FUNÇÃO PARA MULTIPLICAR TODOS OS 9 PRIMEIROS NÚMEROS DO CPF#
            $multiplic=$i*$digitos_1[$i];
            $calcX=$calcX+$multiplic;
            #NA MEDIDA EM QUE FOR FAZENDO A MULTIPLICAÇÃO $CALCX IRÁ SOMANDO TODOS OS VALORES#
        }

        $restoX=$calcX%11;

        if ($restoX==1 or $restoX==0) {
        #SE O RESULTADO DA MULTIPLICAÇÃO ENTRE OS 9 PRIMEIROS DÍGITOS FOR IGUAL A 1 OU 0 ENTÃO A VARIAVEL DO DIGITO X SERÁ 0# 	
            $digX=0;
        }else{
            $digX=11-$restoX;
        }

        $digitos_2 = array(
            '11' => substr($cpf, 0, -13),
            '10' => substr($cpf, 1, -12),
            '9' => substr($cpf, 2, -11),
            '8' => substr($cpf, 4, -9),
            '7' => substr($cpf, 5, -8),
            '6' => substr($cpf, 6, -7),
            '5' => substr($cpf, 8, -5),
            '4' => substr($cpf, 9, -4),
            '3' => substr($cpf, 10, -3),
            '2' => $digX
        );

        $calcY=0;
        for ($i=2; $i < (count($digitos_2)+2); $i++) {
        #FUNÇÃO PARA MULTIPLICAR TODOS OS 9 PRIMEIROS NÚMEROS DO CPF#
            $multiplic2=$i*$digitos_2[$i];
            $calcY=$calcY+$multiplic2;
            #NA MEDIDA EM QUE FOR FAZENDO A MULTIPLICAÇÃO $CALCY IRÁ SOMANDO TODOS OS VALORES#
        }

        $restoY=$calcY%11;

        if ($restoY==1 or $restoY==0) {
        #SE O RESULTADO DA MULTIPLICAÇÃO ENTRE OS 9 PRIMEIROS DÍGITOS FOR IGUAL A 1 OU 0 ENTÃO A VARIAVEL DO DIGITO X SERÁ 0 #
            $digY=0;
        }else{
            $digY=11-$restoY;
        }

        if (substr($cpf, -2, 1)==$digX and substr($cpf, -1, 1)==$digY) {

            $digitos = array(
                '0' => substr($cpf, 0, -13),
                '1' => substr($cpf, 1, -12),
                '2' => substr($cpf, 2, -11),
                '3' => substr($cpf, 4, -9),
                '4' => substr($cpf, 5, -8),
                '5' => substr($cpf, 6, -7),
                '6' => substr($cpf, 8, -5),
                '7' => substr($cpf, 9, -4),
                '8' => substr($cpf, 10, -3),
                '9' => substr($cpf, 12, -1),
                '10' => substr($cpf, 13)
            );

            $repeat=0;

            for ($i=0; $i < count($digitos); $i++) { 
                $repeat=$repeat+$digitos[$i];
            }

            if ($repeat/11==$digitos[0] and $repeat/11==$digitos[1] and $repeat/11==$digitos[9] and $repeat/11==$digitos[10] and $repeat/11==$digitos[8] and $repeat/11==$digitos[5]) {
                return $validation=false;
            }else{
                return $validation=true;
            }

        }else{
            return $validation=false;
        }
    }

?>