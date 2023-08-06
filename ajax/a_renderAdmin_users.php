<?php

    echo "<h1 class='my-5 texto-destaque text-center text-md-start'><span>Usuários</span></h1>";

?>

<br>

<div class="content my-5">
    <div class="row justify-content-center justify-content-md-between">
        <div class="col-12 col-md-auto mb-3">
            <h1 class="texto-destaque text-center text-md-start"><span>Lista de </span> USUÁRIOS</h1>
        </div>
        <div class="col-12 col-md-auto mb-3">
            <button class="btn-new-user" data-bs-toggle="modal" data-bs-target="#modalAction">Novo usuário</button>
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
                        <tr>
                            <td>1</td>
                            <td>Administrador</td>
                            <td>administrator@spark-it.com.br</td>
                            <td>12/08/2023 15:48</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="checkboxStatus" checked disabled>
                                    <label class="form-check-label" for="checkboxStatus">Ativo</label>
                                    </div>
                            </td>
                            <td class="text-end">
                                <button class="btn-edit-user" data-bs-toggle="modal" data-bs-target="#modalAction">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="modalActionContent" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header px-5 py-3">
                <h1 class="modal-title" id="modalActionContent">Adicionar Novo Usuário</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 py-3 my-5">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <label for="" class="custom-form-label">Nome</label>
                        <input type="text" class="person-form-control">
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="" class="custom-form-label">E-mail</label>
                        <input type="text" class="person-form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="" class="custom-form-label">Senha</label>
                        <input type="text" class="person-form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer px-5 py-3">
                <button type="button" class="btn-custom-default" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-custom-default">Adicionar</button>
            </div>
        </div>
    </div>
</div>