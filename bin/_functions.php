<?php
    require_once dirname(__DIR__) . "/vendor/autoload.php";
    require_once __DIR__ . "/config.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // ler todas as classes automaticamente
    spl_autoload_register(function ($className) {
        if (in_array($className, ['mySqlConnection', 'db_crud'], true)) require_once __DIR__ . '/classes/'.$className.'.php';
    });

    // SMTP credentials are resolved only on the server.
    function send_email($message, $subject, $destinyAddr) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = app_config('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = app_config('SMTP_USER');
            $mail->Password = app_config('SMTP_PASSWORD');
            $mail->Port = (int) app_config('SMTP_PORT', '587');
            $mail->SMTPSecure = app_config('SMTP_SECURITY', 'tls');
            if (!(($mail->Port === 587 && $mail->SMTPSecure === 'tls') || ($mail->Port === 465 && $mail->SMTPSecure === 'ssl'))) {
                throw new RuntimeException('Configuração TLS do SMTP inválida.');
            }
            $mail->SMTPOptions = ['ssl' => ['verify_peer' => true, 'verify_peer_name' => true, 'allow_self_signed' => false]];
            $mail->Timeout = 20;
            $mail->CharSet = 'utf-8';
            $mail->setFrom(app_config('SMTP_FROM'), app_config('SMTP_FROM_NAME', 'Paulo Bareta Fotografia'));
            $mail->addAddress($destinyAddr);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = html_entity_decode(strip_tags($message), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $mail->send();
            return true;
        } catch (Throwable $error) {
            error_log('smtp_send_failed');
            return false;
        }
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

    // função para retornar a referencia da página que esta sendo requisitada para buscar os dados relacionados a ela
    function getReference($requestUri) {
        $route = substr($requestUri, 1);
            
        // verifica se tem variavel na url
        if (strpos($route, "?")) {
            $explodedUri = explode("?", $route);

            // aqui eu pego a pagina que esta sendo solicitada
            $route = $explodedUri[0];
        }

        if (strpos($route, "/")) {
            $explodedUri = explode("/", $route);

            // aqui eu pego a pagina que esta sendo solicitada
            $route = $explodedUri[0];
        }

        switch (strtolower($route)) {
            
            case '':
                $reference = "home";
            break;
            
            case 'retratos':
                $reference = "pictures";
            break;
            
            case 'personalidades':
                $reference = "personalities";
            break;
            
            case 'moda':
                $reference = "fashion";
            break;
            
            case 'corporativo':
                $reference = "corporate";
            break;
            
            case 'clientes':
                $reference = "clients";
            break;
            
            case 'sobre':
                $reference = "about";
            break;

            default:
                $reference = "";
            break;
        }

        return $reference;
    }

?>