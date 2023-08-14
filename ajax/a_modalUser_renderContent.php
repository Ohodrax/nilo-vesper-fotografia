<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    $textContent     = "";
    $disableInputs   = "";
    $requirePassword = "";
    $renderContent   = false;
    $refError        = "Ocorreu um erro durante a renderização";
    $formText        = "";
    $dataSet         = "";

    $formInputPerfil = 0;
    $formInputName   = "";
    $formInputEmail  = "";

    if (array_key_exists("renderContent", $_POST)) {
        $content = $_POST['renderContent'];

        switch ($content) {
            case 'new':
                $textContent     = "Adicionar";
                $requirePassword = "required";
                $formText        = "Minimo de 6 caracteres";
                $renderContent   = true;
            break;
            
            case 'edit':
                $textContent   = "Editar";
                $formText      = "Senhas em branco não são alteradas";
                $renderContent = true;

                if (array_key_exists("targetId", $_POST)) {
                    
                    $id_usuario = intval($_POST['targetId']);

                    $filters = array(
                        "id_usuario = ".$id_usuario
                    );
                
                    $orderby = "id_usuario asc";
                
                    $selectUser = new db_crud();
                    $selectUser->table   = "tb_usuario";
                    $selectUser->filters = $filters;
                    $selectUser->orderby = $orderby;
                    $ret_selectUser = $selectUser->default_select_data();

                    if (count($ret_selectUser) == 0) {
                        $renderContent = false;
                        $refError      = "Conta de usuário não encontrada";
                    } else {
                        $renderContent = true;

                        $formInputPerfil = $ret_selectUser[0]['id_perfil'];
                        $formInputName   = $ret_selectUser[0]['nome'];
                        $formInputEmail  = $ret_selectUser[0]['email'];
                    }

                } else {
                    $renderContent = false;
                    $refError      = "Conta de usuário não definida";
                }
                
                $dataSet = "data-target-id='".$id_usuario."'";
            break;
            
            case 'exclude':
                $textContent   = "Excluir";
                $disableInputs = "disabled";
                $renderContent = true;

                if (array_key_exists("targetId", $_POST)) {
                    
                    $id_usuario = intval($_POST['targetId']);

                    $filters = array(
                        "id_usuario = ".$id_usuario
                    );
                
                    $orderby = "id_usuario asc";
                
                    $selectUser = new db_crud();
                    $selectUser->table   = "tb_usuario";
                    $selectUser->filters = $filters;
                    $selectUser->orderby = $orderby;
                    $ret_selectUser = $selectUser->default_select_data();

                    if (count($ret_selectUser) == 0) {
                        $renderContent = false;
                        $refError      = "Conta de usuário não encontrada";
                    } else {
                        $renderContent = true;

                        $formInputPerfil = $ret_selectUser[0]['id_perfil'];
                        $formInputName   = $ret_selectUser[0]['nome'];
                        $formInputEmail  = $ret_selectUser[0]['email'];
                    }

                    $dataSet = "data-target-id='".$id_usuario."'";

                } else {
                    $renderContent = false;
                    $refError      = "Conta de usuário não definida";
                }
            break;
            
            default:
                
            break;
        }
    } else {
        $refError = "Tipo do conteúdo não definido";
    }

    if ($renderContent) {
        $filters = array(
            "status = 1"
        );
    
        $selectProfiles = new db_crud();
        $selectProfiles->table   = "tb_perfil";
        $selectProfiles->filters = $filters;
        $ret_selectProfiles = $selectProfiles->default_select_data();
?>

<div class="modal-content">
    <div class="modal-header px-5 py-3">
        <h1 class="modal-title" id="modalActionContent"><?php echo $textContent; ?> Usuário</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body px-5 py-3 my-5">
        <div class="row">
            <div class="col-12 col-lg-6 mb-3">
                <label for="formName" class="custom-form-label">Nome</label>
                <input type="text" class="person-input-users" <?php echo $disableInputs; ?> id="formName" required name="Nome" value="<?php echo $formInputName; ?>">
            </div>
            <div class="col-12 col-lg-6 mb-3">
                <label for="formEmail" class="custom-form-label">E-mail</label>
                <input type="text" class="person-input-users" <?php echo $disableInputs; ?> id="formEmail" required inputmode="email" name="E-mail" value="<?php echo $formInputEmail; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6 mb-3">
                <label for="formPassword" class="custom-form-label">Senha</label>
                <input type="password" class="person-input-users" <?php echo $disableInputs; echo $requirePassword; ?> id="formPassword" name="Senha">
                <div class="form-text fs-4"><?php echo $formText; ?></div>
            </div>
            <div class="col-12 col-lg-6 mb-3">
                <label for="formPerfil" class="custom-form-label">Perfil de acesso</label>
                <select name="Perfil de acesso" id="formPerfil" class="person-input-users" <?php echo $disableInputs; ?> required>
                    <?php
                        if (count($ret_selectProfiles) > 0) {
                            if ($formInputPerfil == "" || $formInputPerfil == 0) {
                                echo "<option disabled selected value=''>Selecionar...</option>";
                                
                                foreach ($ret_selectProfiles as $key => $value) {
                                    echo "<option value='".$value['id_perfil']."'>".$value['nome']."</option>";
                                }
                            } else {
                                foreach ($ret_selectProfiles as $key => $value) {
                                    if ($formInputPerfil == $value['id_perfil']) {
                                        echo "<option value='".$value['id_perfil']."' selected>".$value['nome']."</option>";
                                    } else {
                                        echo "<option value='".$value['id_perfil']."'>".$value['nome']."</option>";
                                    }
                                }
                            }
                        } else {
                            echo "<option disabled selected>Selecionar...</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer px-5 py-3">
        <input type="hidden" name="action" id="formAction" value="<?php echo $content; ?>">
        <button type="button" class="btn-custom-default" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn-custom-default" <?php echo $dataSet; ?> onclick="performAction(this)"><?php echo $textContent; ?></button>
    </div>
</div>

<?php } else { include "./a_modalContent_renderError.php"; } ?>