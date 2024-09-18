<?php

// include "../../../conexao/conexao.php";
// include "../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php";
// include "/../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_pesquisa_parceiro" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Pesquisar Parceiro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="row">
                    <div class="col-md  mb-2">

                        <div class="input-group">
                            <input type="text" class="form-control" id="pesquisa_conteudo_parceiro" placeholder="Tente pesquisar pela Razão social, Nome fantaisa, código ou cnpj " aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary" type="button" id="pesquisar_parceiro">Pesquisar</button>
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
                <button type="button" class="btn  btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>

<script src="js/include/parceiro/pesquisa_parceiro.js"></script>