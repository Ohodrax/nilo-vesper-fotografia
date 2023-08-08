<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/control/c_getInformations_users.php";

    echo "<h1 class='my-5 texto-destaque text-center text-md-start'><span>Usuários</span></h1>";

?>

<br>

<div class="content my-5">
    <div class="row justify-content-center justify-content-md-between">
        <div class="col-12 col-md-auto mb-3">
            <h1 class="texto-destaque text-center text-md-start"><span>Lista de </span> USUÁRIOS</h1>
        </div>
        <div class="col-12 col-md-auto mb-3">
            <button class="btn-new-user" data-bs-toggle="modal" data-bs-target="#modalAction" data-render-content="new" onclick="renderModalContent(this)">Novo usuário</button>
        </div>
    </div>

    <br>
    <br>

    <div class="row my-5">
        <div class="col-12">
            <div class="table-fluid">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Criado em</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (count($ret_selectUser) == 0) {
                                echo "<tr>";
                                echo    "<td>Nenhum usuário encontrado</td>";
                                echo "</tr>";
                            } else {
                                foreach ($ret_selectUser as $key => $value) {
                                    $inputCheckbox       = boolval($value['status']) ? "checked" : "";
                                    $labelCheckbox       = boolval($value['status']) ? "Ativo" : "Inativo";

                                    $admSecuritySts      = intval($value['id_usuario']) == 1 ? "disabled" : 'onclick="changeStatus(this)" data-target-id="'.$value['id_usuario'].'"';
                                    $admSecurityBtn      = intval($value['id_usuario']) == 1 ? "disabled" : 'data-bs-toggle="modal" data-bs-target="#modalAction" onclick="renderModalContent(this)" data-target-id="'.$value['id_usuario'].'"';
                                    $admSecurityBtnHover = intval($value['id_usuario']) == 1 ? "btn-default-action" : 'btn-default-action btn-default-action-hover';

                                    echo "<tr>";                                        
                                    echo    "<td>".$value['id_usuario']."</td>";
                                    echo    "<td>".$value['nome']."</td>";
                                    echo    "<td>".$value['email']."</td>";
                                    echo    "<td>".date("d/m/Y H:i", strtotime($value['data_criacao']))."</td>";
                                    echo    "<td>";
                                    echo       '<div class="form-check form-switch">';
                                    echo          '<input class="form-check-input" type="checkbox" role="switch" id="checkboxStatus_'.$value['id_usuario'].'" '.$inputCheckbox.' data-render-content="status" '.$admSecuritySts.'>';
                                    echo          '<label class="form-check-label custom-label" id="checkboxLabel_'.$value['id_usuario'].'" for="checkboxStatus_'.$value['id_usuario'].'">'.$labelCheckbox.'</label>';
                                    echo       '</div>';
                                    echo    "</td>";
                                    echo    "<td class='text-end'>";
                                    echo       '<button class="'.$admSecurityBtnHover.'" '.$admSecurityBtn.' data-render-content="edit">';
                                    echo           '<i class="bi bi-pencil"></i>';
                                    echo       '</button>';
                                    echo       '<button class="'.$admSecurityBtnHover.'" '.$admSecurityBtn.' data-render-content="exclude">';
                                    echo           '<i class="bi bi-trash"></i>';
                                    echo       '</button>';
                                    echo    "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="modalActionContent" aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-xl" id="modalAction_content"></div></div>