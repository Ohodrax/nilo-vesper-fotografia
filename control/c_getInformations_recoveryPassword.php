<?php

    // PAGINA PARA PEGAR AS INFORMACOES NO DB DE ACORDO COM O HASH FORNECIDO

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    $requestUri = $_SERVER['REQUEST_URI'];

    // limpa o primeiro caractere "/" da url
    $getQueryData = substr($requestUri, 1);
    //define a var array
    $explodedUri = array();

    if (strpos($getQueryData, "/")) {
        $explodedUri = explode("/", $getQueryData);
    }

    $receivedGetParam = end($explodedUri);

    $filters = array(
        "hash = '".$receivedGetParam."'"
    );

    $selectData = new db_crud();
    $selectData->table   = "tb_recovery";
    $selectData->filters = $filters;
    $ret_selectData = $selectData->default_select_data();

    // link não encontrado
    if (count($ret_selectData) == 0) {
        session_start();

        $_SESSION["showMessage"] = array("return" => "false", "ref" => "Link inválido ou expirado");

        header("Location: /login");
    }

    // link expirado
    if (date("Y-m-d H:i:s") > date("Y-m-d H:i:s", strtotime($ret_selectData[0]['expire_time']))) {
        session_start();

        $_SESSION["showMessage"] = array("return" => "false", "ref" => "Link expirado");

        header("Location: /login");
    }

    // link usado
    if ($ret_selectData[0]['status'] == 0) {
        session_start();

        $_SESSION["showMessage"] = array("return" => "false", "ref" => "Link já utilizado");

        header("Location: /login");
    }

    $arrayData = array("id_usuario" => $ret_selectData[0]['id_usuario'], "hash" => $ret_selectData[0]['hash']);

    // Recovery Account Identification Data
    $raid = base64_encode(json_encode($arrayData));
?>