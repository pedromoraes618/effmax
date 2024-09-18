<?php

include "../../../conexao/conexao.php";
include "../../../modal/venda/venda_mercadoria/gerenciar_venda.php";
include "../../../funcao/funcao.php";


?>
<div class="modal fade" id="modal_adicionar_venda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Venda de Mercadoria</h1>
                <button type="button" class="btn-close fechar_tela_venda" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" id="venda_mercadoria">
                <?php include "../../input_include/usuario_logado.php" ?>
                <input type="hidden" name="codigo_nf" id="codigo_nf" value="<?php echo $codigo_nf; ?>">
                <input type="hidden" id="id" name="id" value="<?php echo $id_nf ?>">
                <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo; ?>">
                <input type="hidden" id="nf_id" name="nf_id" value="">

                <!-- <input type="hidden" id="momento_venda" name="momento_venda" value=""> -->
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                        <label class="bg-primary status_momento_venda"></label>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2  btn-acao">
                        <button type="button" id="iniciar_venda" class="btn btn-sm btn-primary">Iniciar Venda</button>
                        <!-- <button type="button" id="modal_observacao" class="btn btn-sm btn-dark"><i class="bi bi-chat-left"></i> Observação</button> -->
                        <button type="button" id="concluir_venda" onclick="calcula_v_liquido()" class="btn btn-sm btn-success"><i class="bi bi-check2"></i> Concluir</button>
                        <button type="button" class="btn btn-sm btn-secondary fechar_tela_venda" data-bs-dismiss="modal">Fechar</button>
                    </div>

                    <div class="row ">
                        <div class="col card border-0 m-2 p-2 mb-2 shadow">
                            <div class="row mb-2">
                                <div class="col-6 col-md-6  mb-2">
                                    <label for="data_movimento" class="form-label">Data Movimento</label>
                                    <input type="date" class="form-control" <?php if (verficar_paramentro($conecta, "tb_parametros", "cl_id", "15") == "N") {
                                                                                echo 'disabled';
                                                                            } ?> id="data_movimento" name="data_movimento" value="<?= $data_lancamento; ?>">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col mb-2">
                                    <label for="parceiro_descricao" class="form-label">Cliente</label>
                                    <input type="text" class="form-control" readonly id="parceiro_descricao" name="parceiro_descricao" placeholder="Pesquise pelo Cliente/Fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <input type="hidden" class="form-control" name="parceiro_id" id="parceiro_id" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-sm btn-secondary" title="Pesquisar cliente" type="button" name="modal_parceiro" id="modal_parceiro">Pesquisar</button>
                                    <button class="btn btn-sm btn-info" title="Cliente sem cadastro" type="button" name="modal_parceiro_avulso" id="modal_parceiro_avulso">S/Cadastro</button>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col ">
                                    <label for="parceiro_descricao" class="form-label">Observação</label>
                                    <textarea type="hidden" class="form-control" name="observacao" id="observacao" placeholder="Ex. cliente realizou a compra via crédito"></textarea>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-7 m-2  card border-0 p-2 mb-2 shadow">
                            <input type="hidden" class="form-control" name="valor_bruto_venda" id="valor_bruto_venda" value="">
                            <div class=" tabela_externa tabela_modal tabela mb-2" style="height: 353px;">
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 p-2 mb-2 shadow">
                        <div class="row mb-2">
                            <div class="col-md  mb-2">
                                <label for="descricao_produto" class="form-label">Produto</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" <?php if (verficar_paramentro($conecta, "tb_parametros", "cl_id", "14") == "N") {
                                                                                echo 'readonly';
                                                                            } ?> id="descricao_produto" placeholder="Ex. Ferramenta x">
                                    <input type="hidden" class="form-control" name="produto_id" id="produto_id" value="">
                                    <input type="hidden" class="form-control" name="referencia" id="referencia" value="">
                                    <!-- <input type="hidden" class="form-control" name="estoque" id="estoque" value=""> -->
                                    <button class="btn btn-secondary" type="button" name="modal_produto" id="modal_produto">Pesquisar</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-sm-6  col-md  mb-2">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="text" class="form-control inputNumber" placeholder="0.00" onblur="calcular_valor_total()" name="quantidade" id="quantidade" value="">
                            </div>
                            <div class="col-sm-6  col-md  mb-2">
                                <label for="preco_venda" class="form-label">Preço Venda</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control inputNumber" placeholder="0.00" onblur="calcular_desconto(),calcular_valor_total()" name="preco_venda" id="preco_venda" value="">

                                </div>
                            </div>


                            <div class="col-sm-6  col-md  mb-2">
                                <label for="desconto" class="form-label">Desconto</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">%</span>
                                    <input type="text" class="form-control inputNumber" placeholder="0.00" disabled onblur="calcular_valor_total()" name="desconto" id="desconto" value="">
                                </div>
                            </div>
                            <div class="col-sm-6  col-md  mb-2">
                                <label for="valor_total_item" class="form-label">Preço total</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" placeholder="0.00" disabled name="valor_total" id="valor_total" value="">
                                    <button type="button" id="adicionar_produto" onclick="calcular_valor_total()" class="btn btn-success">Adicionar</button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-sm-6  col-md-2 mb-2">
                                <label for="quantidade" class="form-label">Unidade</label>
                                <input type="text" class="form-control" disabled name="unidade" id="unidade" value="">
                            </div>
                            <div class="col-sm-6  col-md-2  mb-2">
                                <label for="estoque" class="form-label">Estoque</label>
                                <input type="text" class="form-control" disabled name="estoque" id="estoque" value="">
                            </div>
                            <div class="col-sm-6  col-md-2  mb-2">
                                <label for="prc_venda_atual" class="form-label">Preço de venda atual</label>
                                <input type="text" class="form-control inputNumber" disabled name="preco_venda_atual" id="preco_venda_atual" value="">
                            </div>
                        </div>

                    </div>

                    <div class="modal_externo_finalizar_venda">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal_externo"></div>
    <div class="modal_externo_fiscal"></div>
</div>


<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/venda/venda_mercadoria/venda_tela.js"></script>
<!-- <script src="js/include/parceiro/pesquisa_parceiro.js"></script> -->