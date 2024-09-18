<?php
include "../../../modal/estoque/ajuste_estoque/gerenciar_ajuste_estoque.php";


?>

<div class="modal fade" id="modal_ajuste_estoque" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Ajuste</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="ajuste_estoque">
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title">Ajuste de Estoque</label>
                    </div>
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="button" id="finalizar_ajuste" class="btn btn-sm btn-success">Finalizar</button>
                            <button type="button" class="btn btn-sm btn-secondary" id="fechar_modal_ajst_estoque" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" readonly id="codigo_nf" value="<?php echo $codigo_nf ?>" name="codigo_nf">

                    <div class="row mb-2">
                        <div class="col-md-auto mb-2">
                            <input type="date" class="form-control" disabled id="data_ajuste" name="data_ajuste" value="<?php echo $data_ajuste; ?>">
                        </div>
                        <div class="col-md-auto mb-2">
                            <input type="text" class="form-control" disabled id="numero_ajuste" name="numero_ajuste" value="<?php echo $numero_ajuste; ?>">
                        </div>
                    </div>
                    <div class="info-prod">
                        <div class="row">
                            <div class="col-sm">
                                <span class="badge rounded-2 mb-3 d-area dv">Produto</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md mb-2">
                                <label for="descricao_produto" class="form-label">Descição</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" disabled id="descricao_produto" placeholder="">
                                    <input type="hidden" class="form-control" name="produto_id" id="produto_id" value="">
                                    <button type="button" class="btn btn-outline-secondary" name="modal_produto" id="modal_produto">Pesquisar</button>
                                </div>
                            </div>

                        </div>
                        <div class="row mb-1">
                            <div class="col-sm-6 col-md-2 mb-2">
                                <label for="estoque" class="form-label">Estoque</label>
                                <input type="text" class="form-control" disabled id="estoque" name="estoque" value="">
                            </div>
                            <div class="col-sm-6 col-md-2 mb-2">
                                <label for="unidade" class="form-label">Unidade</label>
                                <input type="text" class="form-control" disabled id="unidade" name="unidade" value="">
                            </div>
                            <div class="col-sm-6 col-md-3  mb-2">
                                <label for="tipo" class="form-label">Tipo</label>
                                <SELect name="tipo" id="tipo" class="form-control">
                                    <option value="0">Selecione</option>
                                    <option value="ENTRADA">Entrada</option>
                                    <option value="SAIDA">Saida</option>
                                </SELect>
                            </div>
                            <div class="col-sm-6 col-md-2 mb-2">
                                <label for="qtd_ajuste" class="form-label">Quantidade</label>
                                <input type="text" class="form-control" id="qtd_ajuste" name="qtd_ajuste" value="">
                            </div>


                            <div class="col-md  mb-2">
                                <label for="valor_item" class="form-label">Valor item</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" name="valor_item" id="preco_venda_atual" value="">
                                    <button type="submit" id="adicionar_produto_ajuste" class="btn btn-sm btn-success">Adicionar</button>
                                </div>
                                <!-- <input type="hidden" class="form-control" name="valor_total_item" id="valor_total_item" value=""> -->
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-2 mb-2">
                                <label for="data_validade" class="form-label">Data válidade</label>
                                <input type="date" class="form-control " name="data_validade" id="data_validade" value="">
                            </div>
                            <div class="col-md  mb-2">
                                <label for="motivo" class="form-label">Motivo</label>
                                <input type="text" class="form-control" id="motivo" name="motivo" value="">
                            </div>

                        </div>
                    </div>
                    <div class="card p-2 border-0 border-top shadow tabela_externa tabela mb-2">

                    </div>

                </div>
        </div>
        </form>
    </div>
</div>

<div class="modal_externo">

</div>

<script src="js/funcao.js"></script>
<script src="js/estoque/ajuste_estoque/ajuste_tela.js"></script>