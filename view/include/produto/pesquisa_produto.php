<?php

// include "../../../conexao/conexao.php";
// include "../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php";
// include "/../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_pesquisa_produto" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Pesquisar Item</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md  mb-2">
                        <input type="hidden" disabled id="tipo_produto" value="<?= isset($_GET['tipo_produto']) ? $_GET['tipo_produto'] : ''; ?>">
                        <input type="hidden" disabled id="operacao_produto" value="<?= isset($_GET['operacao_produto']) ? $_GET['operacao_produto'] : ''; ?>">
                        <div class="input-group">
                            <input type="text" class="form-control" id="pesquisa_conteudo_produto" placeholder="Tente pesquisar pela descrição, código, referência ou fabricante " aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary" type="button" id="pesquisar_produto">Pesquisar</button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md  mb-2">
                        <div class="alerta"></div>
                        <div class="tabela"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>

<script src="js/include/produto/pesquisa_produto.js"></script>