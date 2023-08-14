<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    session_start();

    $array_return = array("return" => false, "ref" => "");
    $proccess     = true;

    if (!array_key_exists("reference", $_POST) && $proccess) {
        $proccess            = false;
        $array_return['ref'] = "Referencia não identificada";

        $array_return["debug"] = $_POST;
    }
    
    if (!array_key_exists("localization", $_POST) && $proccess) {
        $proccess            = false;
        $array_return['ref'] = "Localização não identificada";

        $array_return["debug"] = $_POST;
    }

    if (count($_FILES) == 0 && $proccess) {
        $proccess            = false;
        $array_return['ref'] = "Imagem não identificada";

        $array_return["debug"] = $_FILES;
    }

    $destinationFolder = $_SERVER['DOCUMENT_ROOT'] . "/assets/" . $_POST['reference'];

    if (!file_exists($destinationFolder) && $proccess) {
        $proccess            = false;
        $array_return['ref'] = "Referencia desconhecida";

        $array_return["debug"] = $destinationFolder . intval(file_exists($destinationFolder));
    }

    if ($proccess) {
        $reference    = $_POST['reference'];
        $localization = $_POST['localization'];

        $supportedFiles = array(
            "image/png",
            "image/jpg",
            "image/jpeg",
        );

        $getFileName     = $reference . "_" . $_FILES[0]['name'];
        $getFileMimeType = mime_content_type($_FILES[0]['tmp_name']);

        // checa se é um arquivo permitido
        if (in_array($getFileMimeType, $supportedFiles) && $proccess) {

            $fileTarget = $destinationFolder . "/" . $getFileName;

            // se a imagem que esta sendo inserida faz parte de um carrossel, então ele vai inserir várias imagens da mesma referencia e do mesmo local, direfenciando apenas o 
            // nome, caso contrário, for de um banner ou capa ou parallax ele sempre vai substituir;
            $carrossel = array(
                "carrossel_principal"
            );

            $replaceImage = in_array($localization, $carrossel) ? 1 : 0;

            // processo de substituição de imagem uma vez que a referencia não é um carrossel
            if (!boolval($replaceImage)) {
                $filters = array(
                    "referencia = '".$reference."'",
                    "localizacao = '".$localization."'",
                );

                $selectReplaceImage = new db_crud();
                $selectReplaceImage->table   = "tb_image";
                $selectReplaceImage->filters = $filters;
                $ret_selectReplaceImage = $selectReplaceImage->default_select_data();

                if (count($ret_selectReplaceImage) > 0) {
                    $replaceTarget = $destinationFolder . "/" . $ret_selectReplaceImage[0]['nome'];

                    if (file_exists($replaceTarget)) {
                        unlink($replaceTarget);
                    }
                }
            }

            if (move_uploaded_file($_FILES[0]['tmp_name'], $fileTarget)) {

                // salvar nova imagem no db
                $array_dataInsert = array(
                    "nome"             => $getFileName,
                    "referencia"       => $reference,
                    "localizacao"      => $localization,
                    "replaceReference" => $replaceImage,
                    "user_ref"         => $_SESSION['userName']
                );

                $saveImageRef = new db_crud();
                $saveImageRef->data = $array_dataInsert;
                $saveImageRef->proc = "proc_insert_image";
                $ret_saveImageRef=$saveImageRef->default_call_proc();

                if ($ret_saveImageRef) {

                    $array_return['return'] = true;
                    $array_return['ref'] = "Arquivo salvo com sucesso";

                    http_response_code(200);
                } else {
                    unlink($fileTarget);
                    
                    http_response_code(500);

                    $array_return['ref'] = "Erro ao salvar arquivo no db";
                }
            } else {
                $array_return['ref'] = "Erro ao enviar arquivo";
            }

        } elseif($proccess) {
            $array_return['ref'] = "Tipo de arquivo não suportado, apenas PNG, JPG e JPEG são permitidos";
        }    
    }

    echo json_encode($array_return);

?>