<?php

// include "../../../conexao/conexao.php";
// include "../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php";
// include "/../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_adiciona_parceiro_avulso" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Cliente Avulso</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="row">
                    <div class="col-md  mb-2">
                        <div class="input-group">
                            <input type="text" class="form-control" id="conteudo_parceiro_avulso" placeholder="Informe o nome do Cliente" aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-primary" type="button" id="adicionar_parceiro_avulso">Adicionar</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md  mb-2">
                        <div class="alerta">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning" id="modal_cadastrar_parceiro" data-bs-dismiss="modal">Cadastrar cliente</button>
                <button type="button" class="btn   btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>
<div class="alert"></div>
<div class="modal_externo_cadastrar_parceiro"></div>

<script src="js/include/parceiro_avulso/adicionar_parceiro.js"></script>