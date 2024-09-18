<?php

// include "../../../conexao/conexao.php";
// include "/../../../funcao/funcao.php";
// include "../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php";

?>
<div class="modal fade" id="modal_pesquisa_servico" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Pesquisar Serviços</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="row">
                    <div class="col-md  mb-2">

                        <div class="input-group">
                            <input type="text" class="form-control" id="pesquisa_conteudo_servico" placeholder="Tente pesquisar pela descrição ou código" aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary" type="button" id="pesquisar_servico">Pesquisar</button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md  mb-2">
                        <div class="alerta">

                        </div>
                        <div class="tabela">

                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>

<script src="js/include/servico/pesquisa_servico.js"></script>