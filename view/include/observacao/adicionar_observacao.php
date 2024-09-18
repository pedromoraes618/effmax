<?php

// include "../../../conexao/conexao.php";
// include "../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php";
// include "/../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_adiciona_observacao" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Observação</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="row">
                    <div class="col-md  mb-2">
                        <textarea class="form-control" placeholder="Digite sua observação..." name="" id="valor_observacao" cols="30" rows="10"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="salvar_observacao" data-bs-dismiss="modal">Adicionar</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>

            </div>

        </div>
    </div>
</div>
<div class="alert"></div>

<script src="js/include/observacao/adicionar_observacao.js"></script>