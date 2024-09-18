<?php

// include "../../../conexao/conexao.php";
// include "../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php";
// include "/../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_adicionar_frete" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Taxa de Entrega</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="adicionar_frete_delivery">
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" class="btn btn-sm btn-success">Adicionar</button>
                            <button type="button" class="btn btn-sm btn-secondary fechar_modal_frete" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md  mb-2">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro">
                        </div>
                        <div class="col-md  mb-2">
                            <label for="valor" class="form-label">Valor</label>
                            <input type="text" class="form-control" id="valor" name="valor">
                        </div>
                        <div class="col-md  mb-2">
                            <label for="data_promocao" class="form-label">Datá Promoção até</label>
                            <input type="date" class="form-control" id="data_promocao" name="data_promocao">
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="js/include/delivery/frete.js"></script>