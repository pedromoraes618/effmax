<?php

include "../../../conexao/conexao.php";
include "../../../modal/venda/venda_mercadoria_delivery/gerenciar_venda.php";
include "../../../funcao/funcao.php";


?>
<div class="modal fade" id="modal_adicionar_venda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl   ">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Venda Mercadoria</h1>
                <button type="button" class="btn-close fechar_tela_venda" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" id="venda_mercadoria">
                <?php include "../../input_include/usuario_logado.php" ?>
                <input type="hidden" name="codigo_nf" id="codigo_nf" value="<?php echo $codigo_nf; ?>">
                <input type="hidden" id="id" name="id" value="<?php echo $id_nf ?>">
                <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo; ?>">
                <!-- <input type="hidden" id="momento_venda" name="momento_venda" value=""> -->
                <input type="hidden" class="form-control" name="observacao" id="observacao">
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                        <label class="bg-primary status_momento_venda"></label>
                    </div>
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                            <button type="button" id="iniciar_venda" class="btn btn-sm btn-primary">Iniciar venda</button>
                            <button type="button" id="modal_observacao" class="btn btn-sm btn-dark"><i class="bi bi-chat-left"></i> Observação</button>
                            <button type="button" id="concluir_venda" onclick="calcula_v_liquido()" class="btn btn-sm btn-success"><i class="bi bi-check2"></i> Concluir</button>
                            <button type="button" class="btn btn-sm btn-secondary fechar_tela_venda" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 card border-0 p-2 mb-2 shadow m-2">
                            <div class="row mb-2">
                                <div class="col-md-6  mb-2">
                                    <label for="data_movimento" class="form-label">Data Movimento</label>
                                    <input type="date" class="form-control" <?php if (verficar_paramentro($conecta, "tb_parametros", "cl_id", "15") == "N") {
                                                                                echo 'disabled';
                                                                            } ?> id="data_movimento" name="data_movimento" value="<?= $data_lancamento; ?>">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md  mb-2">
                                    <label for="parceiro_descricao" class="form-label">Cliente</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="parceiro_descricao" name="parceiro_descricao" placeholder="Nome do cliente" aria-label="Recipient's username" aria-describedby="button-addon2">
                                        <input type="hidden" class="form-control" name="parceiro_id" id="parceiro_id" value="">
                                        <button class="btn btn-secondary" type="button" name="modal_parceiro" id="modal_parceiro">Pesquisar</button>
                                        <!-- <button class="btn btn-info " type="button" name="modal_parceiro_avulso" id="modal_parceiro_avulso">S/Cadastro</button> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md  mb-2">
                                    <label for="parceiro_descricao" class="form-label">Opção de Consumo</label>
                                    <select name="opcao_delivery" class="form-select chosen-select" id="opcao_delivery">
                                        <option value="LOCAL">Consumo no local</option>
                                        <option value="RETIRADA">Retirada</option>
                                        <option value="ENTREGA">Entrega</option>
                                    </select>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md  mb-2">
                                    <label for="parceiro_descricao" class="form-label">Endereço</label>
                                    <input type="text" class="form-control" name="endereco_delivery" placeholder="Endereço do cliente" id="endereco_delivery">
                                </div>
                            </div>



                        </div>
                        <div class="col card border-0 p-2 mb-2 shadow m-2">
                            <input type="hidden" class="form-control" name="valor_bruto_venda" id="valor_bruto_venda" value="">
                            <div class="tabela_externa  tabela_modal_delivery tabela mb-2" style="height: 353px;">
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
                                                                            } ?> id="descricao_produto" placeholder="">
                                    <input type="hidden" class="form-control" name="produto_id" id="produto_id" value="">
                                    <input type="hidden" class="form-control" name="referencia" id="referencia" value="">
                                    <!-- <input type="hidden" class="form-control" name="estoque" id="estoque" value=""> -->
                                    <button class="btn btn-secondary" type="button" name="modal_produto" id="modal_produto">Pesquisar</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md  mb-2">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="text" class="form-control inputNumber" onblur="calcular_valor_total()" name="quantidade" id="quantidade" value="">
                            </div>
                            <div class="col-md  mb-2">
                                <label for="preco_venda" class="form-label">Preço Venda</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control inputNumber" onblur="calcular_desconto(),calcular_valor_total()" name="preco_venda" id="preco_venda" value="">

                                </div>
                            </div>
                            <div class="col-md  mb-2">
                                <label for="desconto" class="form-label">Desconto</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">%</span>
                                    <input type="text" class="form-control inputNumber" disabled onblur="calcular_valor_total()" name="desconto" id="desconto" value="">
                                </div>
                            </div>
                            <div class="col-md  mb-2">
                                <label for="valor_total_item" class="form-label">Preço total</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" disabled name="valor_total" id="valor_total" value="">
                                    <button type="button" id="adicionar_produto" onclick="calcular_valor_total()" class="btn btn-success">Adicionar</button>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-2 mb-2">
                                    <label for="quantidade" class="form-label">Unidade</label>
                                    <input type="text" class="form-control" disabled name="unidade" id="unidade" value="">
                                </div>
                                <div class="col-md-2  mb-2">
                                    <label for="estoque" class="form-label">Estoque</label>
                                    <input type="text" class="form-control" disabled name="estoque" id="estoque" value="">
                                </div>
                                <div class="col-md-2  mb-2">
                                    <label for="prc_venda_atual" class="form-label">Preço de venda atual</label>
                                    <input type="text" class="form-control inputNumber" disabled name="preco_venda_atual" id="preco_venda_atual" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal_externo_finalizar_venda"></div>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal_externo">

</div>


<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/venda/venda_mercadoria_delivery/venda_tela.js"></script>
<!-- <script src="js/include/parceiro/pesquisa_parceiro.js"></script> -->