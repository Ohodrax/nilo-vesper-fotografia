<?php
    include "../bin/_environment.php";

    // $array_data = array(
    //     "nome"         => "Administrador",
    //     "email"        => "rodrigo.cassiano00@gmail.com",
    //     "senha"        => password_hash("1234", PASSWORD_DEFAULT),
    //     "criado_por"   => "SYSTEM",
    //     "alterado_por" => "SYSTEM",
    //     "json_string"  => ""
    // );

    // $insertNewUser = new db_crud();
    // $insertNewUser->table = "tb_usuario";
    // $insertNewUser->data  = $array_data;
    // $ret_insertNewUser = $insertNewUser->default_insert_data();

    // --------------------------------------------------------------------------------------------------------------------------------

    // $array_data = array(
    //     "nome"                  => "Administrador",
    //     "email"                 => "rodrigo.cassiano00@gmail.com",
    //     "senha"                 => password_hash("1234", PASSWORD_DEFAULT),
    //     "alterado_por"          => "SYSTEM",
    //     "data_ultima_alteracao" => date("Y-m-d H:i:s")
    // );

    // $updateUser = new db_crud();
    // $updateUser->id    = 1;
    // $updateUser->table = "tb_usuario";
    // $updateUser->data  = $array_data;
    // $ret_updateUser = $updateUser->default_update_data();

    // --------------------------------------------------------------------------------------------------------------------------------

    // $filters = array(
    //     "id_usuario > 0",
    //     "status = 1"
    // );

    // $orderby = "id_usuario desc";

    // $selectUser = new db_crud();
    // $selectUser->table   = "tb_usuario";
    // $selectUser->filters = $filters;
    // $selectUser->orderby = $orderby;
    // $ret_selectUser = $selectUser->default_select_data();

    // foreach ($ret_selectUser as $key => $value) {
    //     echo "Nome: ".$value['id_usuario']." | ".$value['nome']."<br>";
    // }

    // --------------------------------------------------------------------------------------------------------------------------------

    // $deleteUser = new db_crud();
    // $deleteUser->id    = 1;
    // $deleteUser->table = "tb_usuario";
    // $ret_deleteUser=$deleteUser->default_delete_data();

?>