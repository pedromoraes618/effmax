<?php
include "../../../conexao/conexao.php";
include "../../../modal/venda/venda_mercadoria_delivery/gerenciar_venda.php";
?>

<div class="modal fade" id="modal_adiciona_observacao_prd_delivery" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Observação no Item</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
          
                <div class="row">
                    <div class="col-md  mb-2">
                        <textarea class="form-control" placeholder="Digite sua observação..." name=""
                         id="valor_observacao_prd_delivery" cols="30" rows="10"><?php echo $observacao_produto; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id_item_nf='<?php echo $id_item_nf ?>' id="salvar_observacao_prd_delivery">Alterar</button>
                <button type="button" class="btn btn-sm btn-secondary" id="fechar_modal_observacao_prd_delivery" data-bs-dismiss="modal">Fechar</button>

            </div>

        </div>
    </div>
</div>
<div class="alert"></div>

<script src="js/include/observacao/adicionar_observacao_produto_delivery.js"></script>