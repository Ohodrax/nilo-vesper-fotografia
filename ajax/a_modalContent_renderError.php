<?php require_once dirname(__DIR__) . '/bin/_environment.php'; ?>

<div class="modal-content">
    <div class="modal-header px-5 py-3">
        <h1 class="modal-title" id="modalActionContent">Falha ao renderizar</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body px-5 py-3 my-5">
        <div class="row">
            <div class="col-12 col-lg-6">
                <p class="lead fs-2">Não foi possível carregar o conteúdo.: <?php echo h($refError); ?></p>
            </div>
        </div>
    </div>
    <div class="modal-footer px-5 py-3">
        <button type="button" class="btn-custom-default" data-bs-dismiss="modal">Fechar</button>
    </div>
</div>