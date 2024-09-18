<?php

include "../../../conexao/conexao.php";
include "../../../modal/venda/cotacao_mercadoria/gerenciar_cotacao.php";
include "../../../funcao/funcao.php";


?>
<div class="modal fade" id="modal_adicionar_cotacao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Cotação de mercadoria</h1>
                <button type="button" class="btn-close fechar_tela_cotacao" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" id="cotaca_mercadoria">
                <?php include "../../input_include/usuario_logado.php" ?>

                <input type="hidden" name="codigo_nf" id="codigo_nf" value="<?php echo $codigo_nf; ?>">
                <input type="hidden" id="id" name="id" value="<?php echo $id_nf ?>">
                <!-- <input type="hidden" id="momento_venda" name="momento_venda" value=""> -->

                <div class="modal-body">
                    <div class="title mb-3">
                        <label class="form-label sub-title"></label>
                        <label class="bg-primary status_momento_cotacao"></label>
                    </div>

                    <div class="row  mb-3">
                        <div class="d-grid gap-2 group-btn-acao d-md-flex justify-content-md-end btn-acao">

                            <button type="button" id="iniciar_cotacao" class="btn btn-sm btn-primary"><i class="bi bi-arrow-clockwise"></i> Iniciar Cotação</button>
                            <!-- <button type="button" id="modal_observacao" class="btn btn-sm btn-dark">Observação</button> -->
                            <button type="button" id="gerar_venda" class="btn btn-gerar-venda btn-sm btn-info"><i class="bi bi-arrow-bar-up"></i> Gerar Venda</button>
                            <button type="button" class="btn imprimir btn-sm btn-dark"><i class="bi bi-filetype-pdf"></i> Pdf</button>
                            <!-- <a class='btn btn-sm btn-dark btn-imprimir' target='_blank' href='view/venda/cotacao_mercadoria/pdf/pdf_cotacao.php?pdf_cotacao=true&codigo_nf=<?= $codigo_nf; ?>'>Imprimir</a> -->
                            <button type="button" id="alterar_cotacao" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            <button type="button" id="cancelar_cotacao" class="btn btn-sm btn-danger"><i class="bi bi-check-all"></i> Cancelar</button>
                            <button type="button" class="btn btn-sm btn-secondary fechar_tela_cotacao" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-auto  mb-2">
                            <label for="data_movimento" class="form-label">Data movimento</label>
                            <input type="text" class="form-control" maxlength="10" onkeyup="mascaraData(this);" disabled id="data_movimento" name="data_movimento" value="<?php echo $data_final; ?>">
                        </div>
                        <div class="col-auto  mb-2">
                            <label for="data_fechamento" class="form-label">Data fechamento</label>
                            <input type="date" class="form-control " id="data_fechamento" name="data_fechamento" value="">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="vendedor" class="form-label">Vendedor</label>
                            <select class="form-select chosen-select" name="vendedor" id="vendedor">
                                <option value="0">Selecione</option>

                                <?php
                                $resultados = consulta_linhas_tb_2_filtro($conecta, 'tb_users', 'cl_vendedor', 'SIM', 'cl_ativo', '1');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_usuario']);

                                        echo "<option  value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="status_cotacao" class="form-label">Status cotação</label>
                            <select class="form-select chosen-select" name="status_cotacao" id="status_cotacao">
                                <option value="0">Selecione</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_status_cotacao');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);

                                        echo "<option  value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="validade" class="form-label">Válidade</label>
                            <div class="input-group mb-2">
                                <input type="number" class="form-control" name="validade" id="validade" value="" placeholder="Ex. 7">
                                <span class="input-group-text">Dia(s)</span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2" style="display: none;">
                            <label for="att" class="form-label">Att</label>
                            <input type="text" class="form-control " name="att" id="att" value="">
                        </div>
                    </div>


                    <div class="row mb-2">
                        <div class="col-md-12  mb-2">
                            <label for="parceiro_descricao" class="form-label">Cliente</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="parceiro_descricao" name="parceiro_descricao" placeholder="Pesquise pelo Cliente" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <input type="hidden" class="form-control" name="parceiro_id" id="parceiro_id" value="">
                                <button class="btn btn-secondary" type="button" name="modal_parceiro" id="modal_parceiro">Pesquisar</button>
                                <button class="btn btn-outline-info " type="button" name="modal_parceiro_avulso" id="modal_parceiro_avulso">S/Cadastro</button>
                            </div>
                        </div>
                        <div class="col-md-12  mb-2">
                            <label for="observacao" class="form-label">Observação</label>
                            <textarea rows="2" class="form-control" name="observacao" id="observacao" placeholder="Ex. Prazo de orçamento 30 dias"></textarea>
                        </div>
                    </div>

                    <div class="accordion " id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Valores
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md-3  mb-2">
                                            <label for="valor_produtos" class="form-label">Valor Produtos</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" disabled id="valor_produtos" placeholder="0.00" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3  mb-2">
                                            <label for="valor_desconto" class="form-label">Desconto</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control inputNumber" name="valor_desconto" id="valor_desconto" placeholder="0.00" value="">
                                                <button type="button" id="adicionar_desconto" onblur="calcular_valor_total_cotacao()" class="btn btn-info">Autorizar</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3  mb-2">
                                            <label for="valor_liquido_cotacao" class="form-label">Valor Liquido</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" disabled id="valor_liquido_cotacao" placeholder="0.00" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion " id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                    Item
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md  mb-2">
                                            <label for="descricao_produto" class="form-label">Produto</label>
                                            <div class="input-group">
                                                <input type="text" name="descricao_produto" class="form-control" <?php if (verficar_paramentro($conecta, "tb_parametros", "cl_id", "14") == "N") {
                                                                                                                        echo 'readonly';
                                                                                                                    } ?> id="descricao_produto" placeholder="Descrição do Item">
                                                <input type="hidden" class="form-control" name="produto_id" id="produto_id" value="">
                                                <button class="btn btn-outline-secondary" type="button" name="modal_produto" id="modal_produto">Pesquisar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-md">
                                            <label for="prazo_entrega" class="form-label">Prazo entrega</label>
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control inputNumber" name="prazo_entrega" id="prazo_entrega" placeholder="EX. 7" value="">
                                                <span class="input-group-text">Dia(s)</span>
                                            </div>
                                        </div>
                                        <div class="col-md  ">
                                            <label for="quantidade" class="form-label">Quantidade</label>
                                            <input type="text" class="form-control inputNumber" onblur="calcular_valor_total()" placeholder="0.00" name="quantidade" id="quantidade" value="">
                                        </div>
                                        <div class="col-md  ">
                                            <label for="preco_venda" class="form-label">Preço Venda</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control inputNumber" onblur="calcular_desconto(),calcular_valor_total()" placeholder="0.00" name="preco_venda" id="preco_venda" value="">
                                            </div>
                                        </div>


                                        <div class="col-md ">
                                            <label for="desconto" class="form-label">Desconto</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">%</span>
                                                <input type="text" class="form-control inputNumber" disabled onblur="calcular_valor_total()" name="desconto" id="desconto" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3 ">
                                            <label for="valor_total_item" class="form-label">Preço total</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" disabled name="valor_total" id="valor_total" value="">
                                                <button type="submit" id="adicionar_produto" onclick="calcular_valor_total()" class="btn btn-success">Adicionar</button>
                                            </div>
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

                                <input type="hidden" class="form-control" name="valor_bruto_venda" id="valor_bruto_venda" value="">

                                <div class="card p-2 border-0 border-top shadow tabela_externa tabela_modal tabela mb-2">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal_externo_finalizar_venda">

                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal_externo">

</div>

<?php include '../../../funcao/funcaojavascript.jar'; ?>
<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/venda/cotacao_mercadoria/cotacao_tela.js"></script>
<!-- <script src="js/include/parceiro/pesquisa_parceiro.js"></script> -->