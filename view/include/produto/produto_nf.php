<?php
if (isset($_GET['item_nf'])) {
    $id_item_nf = $_GET['id_item_nf'];
    $serie = $_GET['serie'];
} else {
    $id_item_nf = "";
    $serie = "";
}
?>

<div class="modal fade" id="modal_item_nf" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Item</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-1">
                        <input type="text" disabled class="form-control" name="id_produto_item" id="id_produto_item" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-2">

                        <input type="hidden" class="form-control" name="id_item_nf" id="id_item_nf" value="<?php echo $id_item_nf; ?>">
                        <input type="hidden" class="form-control" name="serie_nf" id="serie_nf" value="<?php echo $serie; ?>">

                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" name="descricao_item" readonly id="descricao_item" value="">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-1  mb-2">
                        <label for="unidade_item" class="form-label">Und</label>
                        <input type="text" class="form-control" disabled name="unidade_item" id="unidade_item" value="">
                    </div>

                    <div class="col-md-2  mb-2">
                        <label for="quantidade_item" class="form-label">Quantidade</label>
                        <input type="text" class="form-control inputNumber" onblur="calcular_valor_total_item()" name="quantidade_item" id="quantidade_item" value="">
                    </div>
                    
                    <div class="col-md  mb-2">
                        <label for="preco_venda_item" class="form-label">Preço Venda</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">R$</span>
                            <input type="text" class="form-control inputNumber" onblur="calcular_desconto_item();calcular_valor_total_item()" name="preco_venda_item" id="preco_venda_item" value="">
                            <input type="hidden" class="form-control inputNumber" name="preco_venda_item_atual" id="preco_venda_item_atual" value="">
                        </div>
                    </div>

                    <div class="col-md  mb-2">
                        <label for="desconto" class="form-label">Desconto</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">%</span>
                            <input type="text" class="form-control inputNumber" disabled onblur="calcular_valor_total_item()" name="desconto_item" id="desconto_item" value="">
                        </div>
                    </div>
                    <div class="col-md  mb-2">
                        <label for="valor_total_item" class="form-label">Preço total</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">R$</span>
                            <input type="text" class="form-control" disabled name="valor_total_item" id="valor_total_item" value="">
                        </div>
                    </div>
                </div>
                <?php if ($serie == "ct") { //ccotação
                ?> <div class="row">
                        <div class="col-md-2">
                            <label for="status_produto" class="form-label">Status produto</label>
                            <select class="form-select chosen-select" name="status_produto" id="status_produto">
                                <option value="0">Selecione</option>
                                <option value="1">Aberto</option>
                                <option value="2">Ganho</option>
                                <option value="3">Perdido</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="prazo_entrega_produto" class="form-label">Prazo entrega</label>
                            <input type="text" class="form-control inputNumber" name="prazo_entrega_produto" id="prazo_entrega_produto" placeholder="EX. 7" value="">
                        </div>

                    </div>
                <?php
                } ?>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="alterar_item"></button>
                <button type="button" class="btn  btn-sm btn-secondary fechar_modal_alterar_item" id="fechar_modal_alterar_item" data-bs-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>
<div class="alert"></div>

<script src="js/funcao_2.js"></script>
<script src="js/include/produto/produto_nf.js"></script>