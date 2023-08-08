<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    $filters = array(
        "id_usuario > 0"
    );

    $orderby = "id_usuario asc";

    $selectUser = new db_crud();
    $selectUser->table   = "tb_usuario";
    $selectUser->filters = $filters;
    $selectUser->orderby = $orderby;
    $ret_selectUser = $selectUser->default_select_data();

?>