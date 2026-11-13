<?php

    $limiter = 8;
    $setFrom = 0;
    
    if (!function_exists("send_email")) {
        include $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

        if (isset($_POST['renderedRef'])) {
            $setFrom = filter_var($_POST['renderedRef'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 10000]]);
            if ($setFrom === false) json_result(false, 'Página inválida.', 400);
        }

        if (isset($_POST['referenceRef'])) {
            $reference = $_POST['referenceRef'];
        }
    }
    
    $reference = image_reference($reference ?? 'pictures');
    $setTo = $setFrom * $limiter;

    $filters = array(
        ['localizacao', '=', 'gallery'],
        ['referencia', '=', $reference],
        ['status', '>', 0]
    );

    $orderby = "id_image desc limit ".$setTo.", ".$limiter;

    $selectImages = new db_crud();
    $selectImages->table   = "tb_image";
    $selectImages->filters = $filters;
    $selectImages->orderby = $orderby;
    $ret_selectImages = $selectImages->default_select_data();

?>